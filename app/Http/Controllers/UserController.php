<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use App\Models\UserModel;
use Config;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Yajra\DataTables\DataTables;

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
            abort(404, 'Invalid ID');
            return false;
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roleData = RoleModel::all(['id', 'role_name']);
        return view('User', ['role' => $roleData]);
    }

    /**
     * Get data for DataTable
     */
    public function getData(Request $request)
    {
        $query = UserModel::with('role');

        return DataTables::of($query)
            ->addColumn('role_name', function ($row) {
                return $row->role ? $row->role->role_name : 'N/A';
            })
            ->addColumn('created_by', function ($row) {
                return $row->created_by ?? 'Super Admin';
            })
            ->addColumn('last_logout_at', function ($row) {
                return $row->last_logout_at
                    ? Carbon::parse($row->last_logout_at)->timezone('Asia/Kolkata')->format('d-m-Y h:i A')
                    : '-';
            })
            ->addColumn('actions', function ($row) {
                $id = encrypt($row->id);
                $editBtn = "<button type='button' class='btn btn-sm btn-light-primary editUserButton'
                            data-bs-toggle='modal' data-bs-target='#editUserModal'
                            data-id='{$id}' data-name='{$row->name}'
                            data-email='{$row->email}' data-role='{$row->role_id}'
                            data-url='" . route('user.update', $id) . "'>
                            Edit
                        </button>";
                $deleteBtn = "<button type='button' class='btn btn-sm btn-light-danger deleteUserButton'
                            data-bs-toggle='modal' data-bs-target='#deleteUserModal'
                            data-id='{$id}' data-name='{$row->name}'
                            data-url='" . route('user.destroy', $id) . "'>
                            Delete
                        </button>";
                return "<div class='d-flex gap-3 align-items-center'>{$editBtn}{$deleteBtn}</div>";
            })
            ->rawColumns(['actions'])
            ->make(true);
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