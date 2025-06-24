<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use App\Models\UserModel;
use Config;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Log;
use Illuminate\Contracts\Encryption\DecryptException;

class UserController extends Controller
{
    public function __construct()
    {
        $dynamicDb = session('dynamic_db');
        if ($dynamicDb) {
            Config::set('database.connections.' . $dynamicDb, [
                'driver' => 'mysql',
                'host' => config('database.connections.mysql.host'),
                'port' => config('database.connections.mysql.port'),
                'database' => $dynamicDb,
                'username' => config('database.connections.mysql.username'),
                'password' => config('database.connections.mysql.password'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ]);
            DB::setDefaultConnection($dynamicDb);
        }
    }

    /**
     * Decrypt the encrypted ID
     */
    protected function decryptId($encryptedId)
    {
        try {
            return decrypt($encryptedId);
        } catch (DecryptException $e) {
            return false;
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 5);
            $allowed = [10, 20, 50];
            if (!in_array($perPage, $allowed)) {
                $perPage = 5;
            }

            $data = UserModel::with('role')->paginate($perPage);

            $roleData = RoleModel::all(['id', 'role_name']);
            $userCount = UserModel::count();

            return view('User', ['data' => $data, 'role' => $roleData, 'userCount' => $userCount]);
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Assign the role first');
        }
    }

    /**
     * Search users with pagination and sorting
     */
    public function search(Request $request)
    {
        $query = $request->get('query', '');
        $sortColumn = $request->get('sort_column', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');

        $allowedColumns = ['name', 'email', 'role_name', 'created_by'];
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'name';
        }

        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        $users = UserModel::query()
            ->join('role', 'user.role_id', '=', 'role.id')
            ->where(function ($q) use ($query) {
                $q->where('user.name', 'like', "%{$query}%")
                ->orWhere('user.email', 'like', "%{$query}%")
                ->orWhere('role_name', 'like', "%{$query}%")
                ->orWhere('user.created_by', 'like', "%{$query}%");
            })
            ->select('user.*')
            ->with('role');

        if ($sortColumn === 'role_name') {
            $users->orderBy('role.role_name', $sortDirection);
        } else {
            $users->orderBy("user.{$sortColumn}", $sortDirection);
        }

        $users = $users->paginate(5);

        $html = '';
        foreach ($users as $user) {
            $html .= '
            <tr>
                <td><span class="text-dark fw-bold d-block fs-6">' . htmlspecialchars($user->name) . '</span></td>
                <td><span class="text-dark fw-bold d-block">' . htmlspecialchars($user->email) . '</span></td>
                <td><span class="text-dark fw-bold d-block">' . htmlspecialchars($user->role->role_name ?? 'N/A') . '</span></td>
                <td><span class="text-dark fw-bold d-block">' . htmlspecialchars($user->created_by ?? 'Super Admin') . '</span></td>
                <td><span class="text-dark fw-bold d-block">' .
                    ($user->last_logout_at
                        ? \Carbon\Carbon::parse($user->last_logout_at)->timezone('Asia/Kolkata')->format('d-m-Y h:i A')
                        : '-') .
                    '</span></td>
                <td>
                    <div class="d-flex gap-3 align-items-center">
                        <button type="button" class="btn btn-sm btn-light-primary editUserButton"
                            data-bs-toggle="modal" data-bs-target="#editUserModal"
                            data-id="' . encrypt($user->id) . '"
                            data-name="' . htmlspecialchars($user->name) . '"
                            data-email="' . htmlspecialchars($user->email) . '"
                            data-role="' . ($user->role ? $user->role->id : '') . '"
                            data-url="' . route('user.update', encrypt($user->id)) . '">Edit</button>
                        <button type="button" class="btn btn-sm btn-light-danger deleteUserButton"
                            data-bs-toggle="modal" data-bs-target="#deleteUserModal"
                            data-id="' . encrypt($user->id) . '"
                            data-name="' . htmlspecialchars($user->name) . '"
                            data-url="' . route('user.destroy', encrypt($user->id)) . '">Delete</button>
                    </div>
                </td>
            </tr>';
        }

        if ($users->isEmpty()) {
            $html = '<tr><td colspan="6" class="text-center text-muted">No search result found</td></tr>';
        }

        return response()->json([
            'html' => $html,
            'pagination' => $users->appends([
                'query' => $query,
                'sort_column' => $sortColumn,
                'sort_direction' => $sortDirection
            ])->links()->render(),
            'count' => $users->total(),
        ]);
    }

    /**
     * Check if email exists
     */
    public function checkEmailExists(Request $request)
    {
        $email = $request->input('email');
        $userId = $request->input('id');

        $usersQuery = DB::table('users')->where('email', $email);
        if ($userId) {
            $decryptedId = $this->decryptId($userId);
            if ($decryptedId) {
                $usersQuery->where('id', '!=', $decryptedId);
            }
        }
        $existsInUsers = $usersQuery->exists();

        $userQuery = DB::table('user')->where('email', $email);
        if ($userId) {
            $decryptedId = $this->decryptId($userId);
            if ($decryptedId) {
                $userQuery->where('id', '!=', $decryptedId);
            }
        }
        $existsInUser = $userQuery->exists();

        $exists = $existsInUsers || $existsInUser;

        return response()->json(['exists' => $exists]);
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'regex:/^[A-Za-z ]{1,100}$/'],
            'email' => ['required', 'email'],
            'password' => ['required', 'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8}$/'],
            'role' => ['required', 'exists:role,id'],
        ]);

        $emailExists = DB::table('users')->where('email', $request->input('email'))->exists() || 
                       DB::table('user')->where('email', $request->input('email'))->exists();

        if ($emailExists) {
            return redirect()->back()->withErrors(['submit' => 'This email is already taken.']);
        }

        $hashPassword = Hash::make($request->input('password'));
        $createdBy = session('login_email');

        UserModel::insert([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $hashPassword,
            'role_id' => $request->input('role'),
            'created_by' => $createdBy,
            'created_at' => Carbon::now()
        ]);
          if ($createdBy === session('login_email')) {
            return redirect()->route('UserTable');
        }
        return redirect()->route('user.index')->with('success', 'User added successfully');
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, string $encryptedId)
    {
        $id = $this->decryptId($encryptedId);
        if ($id === false) {
            abort(404, 'Invalid ID');
        }

        $request->validate([
            'name' => ['required', 'regex:/^[A-Za-z ]{1,100}$/'],
            'email' => ['required', 'email'],
            'role' => ['required', 'exists:role,id'],
        ]);

        $user = UserModel::findOrFail($id);

        if ($request->input('email') !== $user->email) {
            $existingUser = UserModel::where('email', $request->input('email'))->first();
            if ($existingUser) {
                return back()->with('errorss', 'Email already in use.');
            }
        }

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role_id' => $request->input('role'),
        ]);

        return redirect()->route('user.index')->with('success', 'User updated successfully');
    }

    /**
     * Update admin user
     */
    public function admin_update(Request $request)
    {
        $encryptedId = $request->input('id');
        $id = $this->decryptId($encryptedId);
        if ($id === false) {
            abort(404, 'Invalid ID');
        }

        $request->validate([
            'name' => ['required', 'regex:/^[A-Za-z ]{1,100}$/'],
            'email' => ['required', 'email'],
            'role' => ['required', 'exists:role,id'],
        ]);

        $user = UserModel::findOrFail($id);

        if ($request->input('email') !== $user->email) {
            $existingUser = UserModel::where('email', $request->input('email'))->first();
            if ($existingUser) {
                return back()->with('errorss', 'Email already in use.');
            }
        }

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role_id' => $request->input('role'),
        ]);

        return redirect()->route('UserTable')->with('success', 'Admin user updated successfully');
    }

    /**
     * Remove the specified user
     */
    public function destroy(string $encryptedId)
    {
        $id = $this->decryptId($encryptedId);
        if ($id === false) {
            abort(404, 'Invalid ID');
        }

        UserModel::destroy($id);

        return redirect()->route('UserTable')->with('success', 'User deleted successfully');
    }
}
?>
