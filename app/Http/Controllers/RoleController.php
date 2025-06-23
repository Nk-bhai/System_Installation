<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use App\Models\UserModel;
use Config;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class RoleController extends Controller
{
    public function __construct()
    {
        // dd(session('dynamic_db'));
        $dynamicDb = session('dynamic_db');
        // dd($dynamicDb);
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
        // $roleData = RoleModel::paginate(5);
        $roleData = RoleModel::paginate(5);

        // Eager load users only if table exists
        if (Schema::hasTable('user')) {
            $roleData->load('users');
        }

        $roleCount = RoleModel::count();


        $add_message = session()->get('add_message');
        $delete_message = session()->get('delete_message');
        $update_message = session()->get('update_message');
        return view('Role', ['roleData' => $roleData, 'add_message' => $add_message, 'delete_message' => $delete_message, 'update_message' => $update_message, 'roleCount' => $roleCount]);
    }

    // public function search(Request $request)
    // {
    //     $query = $request->get('query', '');

    //     $roles = RoleModel::where('role_name', 'like', "%{$query}%")
    //         ->orWhere('permissions', 'like', "%{$query}%")
    //         ->paginate(5);

    //     $html = '';
    //     foreach ($roles as $rd) {
    //         $permissions = is_array($rd->permissions) ? implode(',', $rd->permissions) : $rd->permissions;

    //         $html .= '
    //     <tr>
    //         <td><span class="text-dark fw-bold d-block fs-6">' . $rd->role_name . '</span></td>
    //         <td><span class="text-dark fw-bold d-block">' . $permissions . '</span></td>
    //         <td>
    //             <div class="d-flex gap-3 align-items-center">
    //                 <button type="button" class="btn btn-sm btn-light-primary editRoleButton"
    //                     data-bs-toggle="modal" data-bs-target="#editRoleModal"
    //                     data-id="' . $rd->id . '"
    //                     data-role-name="' . $rd->role_name . '"
    //                     data-permissions="' . $permissions . '"
    //                     data-url="' . route('role.update', $rd->id) . '">Edit</button>

    //                 <button type="button" class="btn btn-sm btn-light-danger deleteRoleButton"
    //                     data-bs-toggle="modal" data-bs-target="#deleteRoleModal"
    //                     data-id="' . $rd->id . '"
    //                     data-role-name="' . $rd->role_name . '"
    //                     data-url="' . route('role.destroy', $rd->id) . '">Delete</button>

    //             </div>
    //         </td>
    //     </tr>';
    //     }

    //     if ($roles->isEmpty()) {
    //         $html = '<tr><td colspan="3" class="text-center text-muted">No search result found</td></tr>';
    //     }

    //     return response()->json([
    //         'html' => $html,
    //         'pagination' => $roles->appends(['query' => $query])->links()->render(),
    //         'count' => $roles->total(),
    //     ]);
    // }


    public function search(Request $request)
    {
        $query = $request->get('query', '');

        $roles = RoleModel::where('role_name', 'like', "%{$query}%")
            ->orWhere('permissions', 'like', "%{$query}%")
            ->paginate(5);

        $html = '';

        foreach ($roles as $rd) {
            $permissions = is_array($rd->permissions) ? implode(',', $rd->permissions) : $rd->permissions;

            // Check if delete button should be shown
            $hasUsers = !Schema::hasTable('user') || (isset($rd->users) && $rd->users->isEmpty());

            $deleteButton = '';
            if ($hasUsers) {
                $deleteButton = '
            <button type="button" class="btn btn-sm btn-light-danger deleteRoleButton"
                data-bs-toggle="modal" data-bs-target="#deleteRoleModal"
                data-id="' . $rd->id . '"
                data-role-name="' . $rd->role_name . '"
                data-url="' . route('role.destroy', $rd->id) . '">Delete</button>';
            }

            $html .= '
        <tr>
            <td><span class="text-dark fw-bold d-block fs-6">' . $rd->role_name . '</span></td>
            <td><span class="text-dark fw-bold d-block">' . $permissions . '</span></td>
            <td>
                <div class="d-flex gap-3 align-items-center">
                    <button type="button" class="btn btn-sm btn-light-primary editRoleButton"
                        data-bs-toggle="modal" data-bs-target="#editRoleModal"
                        data-id="' . $rd->id . '"
                        data-role-name="' . $rd->role_name . '"
                        data-permissions="' . $permissions . '"
                        data-url="' . route('role.update', $rd->id) . '">Edit</button>
                    ' . $deleteButton . '
                </div>
            </td>
        </tr>';
        }

        if ($roles->isEmpty()) {
            $html = '<tr><td colspan="3" class="text-center text-muted">No search result found</td></tr>';
        }

        return response()->json([
            'html' => $html,
            'pagination' => $roles->appends(['query' => $query])->links()->render(),
            'count' => $roles->total(),
        ]);
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
        // dd($request->all());
        $role_name = $request->input('role_name');
        $permissions = $request->input('permissions');
        
        $permissions_string = implode(',', $permissions);

        RoleModel::insert([
            'role_name' => $role_name,
            'permissions' => $permissions_string
        ]);
        return redirect()->route('role.index')->with(['add_message' => "Role Added Successfully"]);
    }

    public function checkRoleName(Request $request)
{
    $roleName = $request->input('role_name');
    $roleId = $request->input('role_id'); // for edit

    $query = DB::table('role')->where('role_name', $roleName);

    if ($roleId) {
        $query->where('id', '!=', $roleId); // exclude current role from check
    }

    $exists = $query->exists();

    return response()->json(['exists' => $exists]);
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
    // public function update(Request $request, string $id)
    // {
    //     // role_name change globally in all table
    //     $role = RoleModel::find($id);
    //     $old_role_name = $role->role_name;
    //     $new_role_name = $request->input('role_name');

    //     $permissions = $request->input('permissions');
    //     // $permissions_string = "";
    //     $permissions_string = implode(',', $permissions);


    //     RoleModel::where('id', '=', $id)->update([
    //         'role_name' => $request->input('role_name'),
    //         'permissions' => $permissions_string
    //     ]);

    //     // role_name change globally in all table
    //     if (Schema::hasTable('user')) {
    //         UserModel::where('role', $old_role_name)->update(['role' => $new_role_name]);
    //     }
    //     $redirect = redirect()->route('role.index')->with(['update_message' => "role Update Successfully"]);

    //     return $redirect;
    // }
    public function update(Request $request, string $id)
    {
        $role = RoleModel::find($id);
        $permissions = $request->input('permissions');
        $permissions_string = implode(',', $permissions);

        $role->update([
            'role_name' => $request->input('role_name'),
            'permissions' => $permissions_string,
        ]);

        return redirect()->route('role.index')->with(['update_message' => "Role updated successfully"]);
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

