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
     * Display a listing of the resource.
     */
    public function index()
    {

        try {
            // $role = RoleModel::get('role_name');
            $role = RoleModel::all();
            // $data = UserModel::paginate(5);
            $data = UserModel::with('role')->paginate(5);
            $userCount = UserModel::count();
            return view('User', ['data' => $data, 'role' => $role, 'userCount' => $userCount]);
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Assign the role First');
        }
    }


    public function search(Request $request)
    {
        $query = $request->get('query', '');

        $users = UserModel::query()
            ->join('role', 'user.role_id', '=', 'role.id')
            ->where(function ($q) use ($query) {
                $q->where('user.name', 'like', "%{$query}%")
                    ->orWhere('user.email', 'like', "%{$query}%")
                    ->orWhere('role.role_name', 'like', "%{$query}%")
                    ->orWhere('user.created_by', 'like', "%{$query}%");
            })
            ->select('user.*') // Select only user columns to avoid ambiguity
            ->with('role')
            ->paginate(5);

        // Render the HTML for the table rows
        $html = '';
        foreach ($users as $dt) {
            $html .= '
        <tr>
            <td><span class="text-dark fw-bold d-block fs-6">' . htmlspecialchars($dt->name) . '</span></td>
            <td><span class="text-dark fw-bold d-block">' . htmlspecialchars($dt->email) . '</span></td>
            <td><span class="text-dark fw-bold d-block">' . htmlspecialchars($dt->role->role_name ?? 'N/A') . '</span></td>
            <td><span class="text-dark fw-bold d-block">' . htmlspecialchars($dt->created_by ?? 'Super Admin') . '</span></td>
            <td><span class="text-dark fw-bold d-block">' .
                ($dt->last_logout_at
                    ? \Carbon\Carbon::parse($dt->last_logout_at)->timezone("Asia/Kolkata")->format("d-m-Y h:i A")
                    : '-') .
                '</span></td>
            <td>
                <div class="d-flex gap-3 align-items-center">
                    <button type="button" class="btn btn-sm btn-light-primary editUserButton"
                        data-bs-toggle="modal" data-bs-target="#editUserModal"
                        data-id="' . $dt->id . '" 
                        data-name="' . htmlspecialchars($dt->name) . '"
                        data-email="' . htmlspecialchars($dt->email) . '" 
                        data-role="' . ($dt->role ? $dt->role->id : '') . '"
                        data-url="' . route('user.update', $dt->id) . '">Edit</button>
                    <button type="button" class="btn btn-sm btn-light-danger deleteUserButton"
                        data-bs-toggle="modal" data-bs-target="#deleteUserModal"
                        data-id="' . $dt->id . '" 
                        data-name="' . htmlspecialchars($dt->name) . '"
                        data-url="' . route('user.destroy', $dt->id) . '">Delete</button>
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
    public function create()
    {
        //
    }

//     public function checkEmailExists(Request $request)
// {
//     $email = $request->input('email');
//     $exists = DB::table('users')->where('email', $email)->exists();

//     return response()->json(['exists' => $exists]);
// }

public function checkEmailExists(Request $request)
{
    $email = $request->input('email');
    $userId = $request->input('user_id'); // null for create

    // Check in 'users' table
    $usersQuery = DB::table('users')->where('email', $email);
    if ($userId) {
        $usersQuery->where('id', '!=', $userId);
    }
    $existsInUsers = $usersQuery->exists();

    // Check in 'user' table
    $userQuery = DB::table('user')->where('email', $email);
    if ($userId) {
        $userQuery->where('id', '!=', $userId);
    }
    $existsInUser = $userQuery->exists();

    $exists = $existsInUsers || $existsInUser;

    return response()->json(['exists' => $exists]);
}

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => ['required', 'min:2'],
            'email' => ['required', 'min:2', 'email'],
            'password' => ['required', 'min:2', 'max:8'],
            'role' => ['required'],
        ]);

        $emailExists = DB::table('users')->where('email', $request->input('email'))->exists();

        if ($emailExists) {
            // dd("he");
            return redirect()->back()->withErrors(['email' => 'This email is already taken.']);
            
        }
        $hashpassword = Hash::make($request->input('password'));

        $createdBy = session('login_email');
        UserModel::insert([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $hashpassword,
            'role_id' => $request->input('role'),
            'created_by' => $createdBy,
            'created_at' => Carbon::now()
        ]);

        if ($createdBy === session('login_email')) {
            return redirect()->route('UserTable');
        }

        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = UserModel::where('id', '=', $id)->get();
        $role = RoleModel::get('role_name');

        return view('UserEdit', ['data' => $data, 'role' => $role]);
    }
    public function User_Table_edit(Request $request)
    {
        $id = $request->input('id');
        $data = UserModel::where('id', '=', $id)->get();
        $role = RoleModel::get('role_name');

        return view('UserTableEdit', ['data' => $data, 'role' => $role]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'min:2'],
            'email' => ['required', 'min:2', 'email'],
            'role' => ['required'],
        ]);

        Log::info('UPDATE REQUEST', [
            'id' => $id,
            'method' => $request->method(),
            'data' => $request->all(),
        ]);

        $user = UserModel::findOrFail($id);
        // Check if email has been changed
        if ($request->input('email') !== $user->email) {
            // dd("hello");
            $existingUser = UserModel::where('email', $request->input('email'))->first();
            if ($existingUser) {
                return back()->with('errorss', 'Email already in use.');
            }
        }

        UserModel::where('id', '=', $id)->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role_id' => $request->input('role'),
        ]);
        return redirect()->route('user.index');
    }
    public function admin_update(Request $request)
    {
        $id = $request->input('id');
        UserModel::where('id', '=', $id)->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role_id' => $request->input('role'),
        ]);
        return redirect()->route('UserTable');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        UserModel::where('id', '=', $id)->delete();
        return redirect()->route('UserTable');
    }
}
