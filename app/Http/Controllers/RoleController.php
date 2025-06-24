<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use Config;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Contracts\Encryption\DecryptException;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
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
        return view('Role');
    }

    /**
     * Get data for DataTable
     */
    public function getData(Request $request)
    {
        $query = RoleModel::query();

        if (Schema::hasTable('user')) {
            $query->with('users');
        }

        return DataTables::of($query)
            ->addColumn('permissions', function ($row) {
                return is_array($row->permissions) ? implode(', ', $row->permissions) : $row->permissions;
            })
            ->addColumn('actions', function ($row) {
                $id = encrypt($row->id);
                $hasUsers = !Schema::hasTable('user') || ($row->users && $row->users->isEmpty());

                $editBtn = "<button type='button' class='btn btn-sm btn-light-primary editRoleButton'
                            data-bs-toggle='modal' data-bs-target='#editRoleModal'
                            data-id='{$id}' data-role-name='{$row->role_name}'
                            data-permissions='{$row->permissions}'
                            data-url='" . route('role.update', $id) . "'>
                            Edit
                        </button>";

                $deleteBtn = $hasUsers ? "<button type='button' class='btn btn-sm btn-light-danger deleteRoleButton'
                            data-bs-toggle='modal' data-bs-target='#deleteRoleModal'
                            data-id='{$id}' data-role-name='{$row->role_name}'
                            data-url='" . route('role.destroy', $id) . "'>
                            Delete
                        </button>" : '';

                return "<div class='d-flex gap-3 align-items-center'>{$editBtn}{$deleteBtn}</div>";
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Store a newly created role
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
        $permissions_string = implode(',', $permissions);

        RoleModel::insert([
            'role_name' => $role_name,
            'permissions' => $permissions_string
        ]);

        return redirect()->route('role.index')->with(['add_message' => "Role Added Successfully"]);
    }

    /**
     * Check if role name exists
     */
    public function checkRoleName(Request $request)
    {
        $roleName = $request->input('role_name');
        $roleId = $request->input('id');

        $query = DB::table('role')->where('role_name', $roleName);

        if ($roleId) {
            $decryptedId = $this->decryptId($roleId);
            if ($decryptedId) {
                $query->where('id', '!=', $decryptedId);
            } else {
                return response()->json(['exists' => false]);
            }
        }

        $exists = $query->exists();

        return response()->json(['exists' => $exists]);
    }

    /**
     * Update the specified role
     */
    public function update(Request $request, string $id)
    {
        $id = $this->decryptId($id);
        if (!$id) {
            abort(404, 'Invalid ID');
        }

        $request->validate([
            'role_name' => ['required', 'string', 'max:100', 'regex:/^[A-Za-z ]+$/', 'unique:role,role_name,' . $id],
            'permissions' => ['required', 'array', 'min:1'],
            'permissions.*' => ['in:View,Create,Delete,Update'],
        ]);

        $role = RoleModel::findOrFail($id);
        $permissions = $request->input('permissions');
        $permissions_string = implode(',', $permissions);

        $role->update([
            'role_name' => $request->input('role_name'),
            'permissions' => $permissions_string,
        ]);

        return redirect()->route('role.index')->with(['update_message' => "Role updated successfully"]);
    }

    /**
     * Remove the specified role
     */
    public function destroy(string $id)
    {
        $id = $this->decryptId($id);
        if (!$id) {
            abort(404, 'Invalid ID');
        }

        RoleModel::destroy($id);

        session()->flash('delete_message', 'Role deleted successfully');
        return redirect()->route('role.index');
    }
}
?>