<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use App\Models\UserModel;
use Config;
use DB;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        // dd(session('dynamic_db'));
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
        // Debug: Log the entire session
        \Log::info('Session data in index method:', session()->all());
        $roleData = RoleModel::paginate(5);
        $add_message = session()->get('add_message');
        $delete_message = session()->get('delete_message');
        $update_message = session()->get('update_message');
        // Debug: Log the retrieved messages
        \Log::info("Retrieved messages - add: {$add_message}, update: {$update_message}, delete: {$delete_message}");
        return view('Role', ['roleData' => $roleData, 'add_message' => $add_message, 'delete_message' => $delete_message, 'update_message' => $update_message]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_name' => ['required', 'string', 'max:100', 'regex:/^[A-Za-z ]+$/', 'unique:role,role_name'],
            'permissions' => ['required', 'array', 'min:1'],
            'permissions.*' => ['in:View,Create,Delete,Update'],
        ]);
        $role_name = $request->input('role_name');
        $permissions = $request->input('permissions');
        // $permissions_string = "";
        // foreach ($permissions as $p) {
        //     $permissions_string .= $p . ',';
        // }
        $permissions_string = implode(',', $permissions);

        RoleModel::insert([
            'role_name' => $role_name,
            'permissions' => $permissions_string
        ]);
        return redirect()->route('role.index')->with(['add_message' => "Role Added Successfully"]);
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
        $role_edit_data = RoleModel::where('id', '=', $id)->get();
        $role = RoleModel::where('id', '=', $id)->first();

        $permissions = [];

        if ($role && $role->permissions) {
            $permissions = array_map('trim', explode(',', $role->permissions));
        }


        return view('RoleEdit', ['role_edit_data' => $role_edit_data, 'permissions' => $permissions]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // role_name change globally in all table
        $role = RoleModel::find($id);
        $old_role_name = $role->role_name;
        $new_role_name = $request->input('role_name');

        $permissions = $request->input('permissions');
        // $permissions_string = "";
        $permissions_string = implode(',', $permissions);


        RoleModel::where('id', '=', $id)->update([
            'role_name' => $request->input('role_name'),
            'permissions' => $permissions_string
        ]);

        // role_name change globally in all table
        UserModel::where('role', $old_role_name)->update(['role' => $new_role_name]);
        $redirect = redirect()->route('role.index')->with(['update_message' => "role Update Successfully"]);
        // Debug: Log the session data immediately after setting it
        \Log::info('Session data after setting update_message:', session()->all());

        return $redirect;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        RoleModel::where('id', '=', $id)->delete();
        // return redirect()->route('role.index')->with(['delete_message' => "role Deleted Successfully"]);
        session()->flash('delete_message', 'role Deleted Successfully');
        return redirect()->route('role.index');
    }
}
