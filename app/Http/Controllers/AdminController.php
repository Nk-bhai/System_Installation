<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use App\Models\UserModel;
use Artisan;
use Config;
use DB;
use Hash;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;


class AdminController extends Controller
{

    public function __construct(Request $request)
    {
        // dd(session('database_name'));
        if (!empty(session('database_name'))) {
            $databasename = session('database_name');
        } else {
            $ip_address = $request->ip();
            $response = Http::get("http://192.168.1.4:8005/api/superadmin/get/{$ip_address}");
            $keyData = $response->json();
            if ($keyData['ip_address'] == $ip_address && $keyData['verified'] == 1) {
                $databasename = $keyData['database'];
                session(['superadmin_profile_logo' => $keyData['profile_logo']]);
                session(['sidebar_logo' => $keyData['sidebar_logo']]);
                session(['favicon' => $keyData['favicon']]);
                session(['superadmin_email' => $keyData['email']]);
                session(['superadmin_password' => $keyData['password']]);
            }

        }

        Config::set('database.connections.' . $databasename, [
            'driver' => 'mysql',
            'host' => config('database.connections.mysql.host'),
            'port' => config('database.connections.mysql.port'),
            'database' => $databasename,
            'username' => config('database.connections.mysql.username'),
            'password' => config('database.connections.mysql.password'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);

        session(['dynamic_db' => $databasename]);
        DB::setDefaultConnection($databasename);

    }

    public function admin(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        try {
            $user = UserModel::where('email', $email)->first();
            if (!$user || !Hash::check($password, $user->password)) {
                return false; // Authentication failed
            }

            // Authentication successful, set session and return true
            session(['login_email' => $email]);
            return true;

        } catch (\Illuminate\Database\QueryException $e) {
            return false; // Database error, treat as authentication failure
        }
    }


    public function dashboardPage(Request $request)
    {
        try {
            $roleCount = RoleModel::count();
        } catch (\Exception $e) {
            $roleCount = 0;
        }

        try {
            // $userCount = UserModel::count();
            if (session('login_email')) {
                $userCount = UserModel::where('created_by', session('login_email'))->count();
            } else {
                $userCount = UserModel::count();
            }
        } catch (\Exception $e) {
            $userCount = 0;
        }

        // Pass the counts to the Dashboard view
        return view('Dashboard', [
            'roleCount' => $roleCount,
            'userCount' => $userCount,

        ]);
    }

    public function UserCrudInstall()
    {

        if (session('login_email')) {
            return redirect()->route('UserTable');
        }
        Artisan::call('migrate', [
            '--database' => session('dynamic_db'),
            '--path' => 'database/migrations/2025_06_04_093250_user_crud.php',
        ]);

        return redirect()->route('user.index');
    }

    public function roleInstall()
    {
        // dd("Hello");
        Artisan::call('migrate', [
            '--database' => session('dynamic_db'),
            '--path' => 'database/migrations/2025_06_05_112216_role.php',
        ]);
        // dd("Hello");

        return redirect()->route('role.index');
    }

    public function UserTable(Request $request)
    {
        $perPage = $request->input('per_page');
        $allowed = [10, 20, 50];
        if (!in_array($perPage, $allowed)) {
            $perPage = 5;
        }
        if ($perPage) {
            $data = UserModel::where('created_by', session('login_email'))->paginate($perPage);
        } else {
            $data = UserModel::where('created_by', session('login_email'))->paginate(5);
        }
        // $data = UserModel::where('created_by', session('login_email'))->paginate(5);

        $user_name = UserModel::where('email', '=', session('login_email'))->get('name');
        $login_name = UserModel::where('email', session('login_email'))->value('name');
        session(['login_name' => $login_name]);
        
        $user = UserModel::where('email', session('login_email'))->first();
        $allrole = RoleModel::all();

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        $role = $user->role;

        $permissions = [];

        if ($role && $role->permissions) {
            $permissions = array_map('trim', explode(',', $role->permissions));
        }

        // Check if user has Create permission
        if (!in_array('Create', $permissions)) {
            session(['without_create' => "1"]);
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access the User Management page.');
        }

        return view('UserTable', ['user_name' => $user_name, 'data' => $data, 'permissions' => $permissions, 'allrole' => $allrole]);
    }

    public function search(Request $request)
    {
        $sortColumn = $request->input('sort_column', 'name');
        $sortDirection = $request->input('sort_direction', 'asc');

        // Whitelist allowed columns
        $allowedColumns = ['name', 'email', 'role_name', 'last_logout_at'];
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'name';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }
        $query = $request->get('query', '');

        $users = UserModel::query()
            ->join('role', 'user.role_id', '=', 'role.id')
            ->where('user.created_by', session('login_email')) // Restrict to users created by the logged-in user
            ->where(function ($q) use ($query) {
                if (!empty($query)) { // Apply search filters only if query is not empty
                    $q->where('user.name', 'like', "%{$query}%")
                        ->orWhere('user.email', 'like', "%{$query}%")
                        ->orWhere('role.role_name', 'like', "%{$query}%");
                }
            })
            ->select('user.*') // Avoid column ambiguity
            ->with('role'); // Eager load role

        // Apply sorting logic
        if ($sortColumn === 'role_name') {
            $users = $users->orderBy('role.role_name', $sortDirection);
        } else {
            $users = $users->orderBy("user.$sortColumn", $sortDirection);
        }

        $users = $users->paginate(5);

        // Get permissions for the logged-in user
        $user = UserModel::where('email', session('login_email'))->first();
        $permissions = $user && $user->role && $user->role->permissions
            ? array_map('trim', explode(',', $user->role->permissions))
            : [];

        // Generate HTML for table rows
        $html = '';
        foreach ($users as $dt) {
            $html .= '
        <tr>
            <td><span class="text-dark fw-bold d-block fs-6">' . htmlspecialchars($dt->name) . '</span></td>
            <td><span class="text-dark fw-bold d-block">' . htmlspecialchars($dt->email) . '</span></td>
            <td><span class="text-dark fw-bold d-block">' . htmlspecialchars($dt->role->role_name) . '</span></td>
            <td><span class="text-dark fw-bold d-block">' .
                ($dt->last_logout_at
                    ? \Carbon\Carbon::parse($dt->last_logout_at)->timezone("Asia/Kolkata")->format("d-m-Y h:i A")
                    : '-') .
                '</span></td>
            <td class="text-center">
                <div class="d-flex gap-3 align-items-center justify-content-center">';

            // Include Edit button only if user has Update permission
            if (in_array('Update', $permissions)) {
                $html .= '
                    <button type="button" class="btn btn-sm btn-light-primary editUserButton"
                        data-bs-toggle="modal" data-bs-target="#editUserModal"
                        data-id="' . $dt->id . '" data-name="' . htmlspecialchars($dt->name) . '"
                        data-email="' . htmlspecialchars($dt->email) . '" data-role="' . $dt->role->id . '"
                        data-url="' . route('admin_update', $dt->id) . '">Edit</button>';
            }

            // Include Delete button only if user has Delete permission
            if (in_array('Delete', $permissions)) {
                $html .= '
                    <button type="button" class="btn btn-sm btn-light-danger deleteUserButton"
                        data-bs-toggle="modal" data-bs-target="#deleteUserModal"
                        data-id="' . $dt->id . '" data-name="' . htmlspecialchars($dt->name) . '"
                        data-url="' . route('user.destroy', $dt->id) . '">Delete</button>';
            }

            $html .= '
                </div>
            </td>
        </tr>';
        }

        if ($users->isEmpty()) {
            $html = '<tr><td colspan="6" class="text-center text-muted">No search result found</td></tr>';
        }

        return response()->json([
            'html' => $html,
            'pagination' => $users->appends(['query' => $query])->links()->render(),
            'count' => $users->total(),
        ]);
    }


    public function logout(Request $request)
    {
        // dd("reach");
        if (Schema::hasTable('user')) {
            $user = UserModel::where('email', session('login_email'))->first();

            if ($user) {
                $user->last_logout_at = now();
                $user->save();
            }
        }
        $request->session()->forget('login_email');
        $request->session()->forget('user_logged_in');
        $request->session()->forget('without_create');
        $request->session()->forget('profile_logo');
        $request->session()->forget('superadmin_email');
        // dd(session('user_logged_in'));
        return redirect()->route('system.auth.login')->with('message', 'Logged out successfully');
    }
}


