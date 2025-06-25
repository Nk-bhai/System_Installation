<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use App\Models\UserModel;
use Artisan;
use Carbon\Carbon;
use Config;
use DB;
use Hash;
use Http;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\DataTables;


class AdminController extends Controller
{

    public function __construct(Request $request)
    {
        // dd(session('database_name'));
        if (!empty(session('database_name'))) {
            $databasename = session('database_name');
        } else {
            $ip_address = $request->ip();
            $response = Http::get("http://192.168.12.79:8005/api/superadmin/get/{$ip_address}");
            $keyData = $response->json();
            if ($keyData['ip_address'] == $ip_address && $keyData['verified'] == 1) {
                $databasename = $keyData['database'];
                session(['superadmin_profile_logo' => $keyData['profile_logo']]);
                session(['sidebar_logo' => $keyData['sidebar_logo']]);
                session(['favicon' => $keyData['favicon']]);
                session(['copyright' => $keyData['copyright']]);
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

    protected function decryptId($encryptedId)
    {
        try {
            return decrypt($encryptedId);
        } catch (DecryptException $e) {
            \Log::error('Decryption error: ' . $e->getMessage());
            abort(404, 'Invalid ID');
        }
    }

 public function UserTable(Request $request)
    {
        $user_name = UserModel::where('email', session('login_email'))->get(['name']);
        $login_name = UserModel::where('email', session('login_email'))->value('name');
        session(['login_name' => $login_name]);

        $user = UserModel::where('email', session('login_email'))->first();
        $allrole = RoleModel::all(['id', 'role_name']);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        $role = $user->role;
        $permissions = $role && $role->permissions ? array_map('trim', explode(',', $role->permissions)) : [];

        if (!in_array('Create', $permissions)) {
            session(['without_create' => "1"]);
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access the User Management page.');
        }

        return view('UserTable', compact('user_name', 'permissions', 'allrole'));
    }

    /**
     * Get data for UserTable DataTable
     */
    public function getUserTableData(Request $request)
    {
        $user = UserModel::where('email', session('login_email'))->first();
        $permissions = $user && $user->role && $user->role->permissions
            ? array_map('trim', explode(',', $user->role->permissions))
            : [];

        $query = UserModel::with('role')->where('created_by', session('login_email'));

        return DataTables::of($query)
            ->addColumn('role_name', function ($row) {
                return $row->role ? $row->role->role_name : 'N/A';
            })
            ->addColumn('last_logout_at', function ($row) {
                return $row->last_logout_at
                    ? Carbon::parse($row->last_logout_at)->timezone('Asia/Kolkata')->format('d-m-Y h:i A')
                    : '-';
            })
            ->addColumn('actions', function ($row) use ($permissions) {
                $id = encrypt($row->id);
                $html = "<div class='d-flex gap-3 align-items-center justify-content-center'>";
                if (in_array('Update', $permissions)) {
                    $html .= "<button type='button' class='btn btn-sm btn-light-primary editUserButton'
                              data-bs-toggle='modal' data-bs-target='#editUserModal'
                              data-id='{$id}' data-name='{$row->name}'
                              data-email='{$row->email}' data-role='{$row->role_id}'
                              data-url='" . route('admin_update') . "'>
                              Edit
                          </button>";
                }
                if (in_array('Delete', $permissions)) {
                    $html .= "<button type='button' class='btn btn-sm btn-light-danger deleteUserButton'
                              data-bs-toggle='modal' data-bs-target='#deleteUserModal'
                              data-id='{$id}' data-name='{$row->name}'
                              data-url='" . route('user.destroy', $id) . "'>
                              Delete
                          </button>";
                }
                $html .= "</div>";
                return $html;
            })
            ->rawColumns(['actions'])
            ->make(true);
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


