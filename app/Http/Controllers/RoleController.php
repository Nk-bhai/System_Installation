<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use App\Models\UserModel;
use Config;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Contracts\Encryption\DecryptException;

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
            return decrypt($encryptedId); // Fixed: Use $encryptedId directly
        } catch (DecryptException $e) {
            abort(404, 'Invalid ID');
            return false;
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 5);
        $allowed = [10, 20, 50];
        if (!in_array($perPage, $allowed)) {
            $perPage = 5;
        }

        $sortColumn = $request->input('sort_column', 'role_name');
        $sortDirection = $request->input('sort_direction', 'asc');

        $allowedColumns = ['role_name', 'permissions'];
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'role_name';
        }

        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        $roleData = RoleModel::orderBy($sortColumn, $sortDirection)->paginate($perPage);

        if (Schema::hasTable('user')) {
            $roleData->load('users');
        }

        $roleCount = RoleModel::count();

        return view('Role', ['roleData' => $roleData, 'roleCount' => $roleCount]);
    }

    /**
     * Search roles with pagination and sorting
     */
    public function search(Request $request)
    {
        $query = $request->get('query', '');

        $sortColumn = $request->get('sort_column', 'role_name');
        $sortDirection = $request->get('sort_direction', 'asc');

        $allowedColumns = ['role_name', 'permissions'];
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'role_name';
        }

        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        $roles = RoleModel::where(function ($q) use ($query) {
            $q->where('role_name', 'like', "%{$query}%")
              ->orWhere('permissions', 'like', "%{$query}%");
        })
        ->orderBy($sortColumn, $sortDirection)
        ->paginate(5);

        $html = '';

        foreach ($roles as $rd) {
            $permissions = is_array($rd->permissions) ? implode(',', $rd->permissions) : $rd->permissions;

            $hasUsers = !Schema::hasTable('user') || (isset($rd->users) && $rd->users->isEmpty());

            $deleteButton = '';
            if ($hasUsers) {
                $deleteButton = '
                    <button type="button" class="btn btn-sm btn-light-danger deleteRoleButton"
                        data-bs-toggle="modal" data-bs-target="#deleteRoleModal"
                        data-id="' . encrypt($rd->id) . '"
                        data-role-name="' . $rd->role_name . '"
                        data-url="' . route('role.destroy', encrypt($rd->id)) . '">Delete</button>';
            }

            $html .= '
                <tr>
                    <td><span class="text-dark fw-bold d-block fs-6">' . $rd->role_name . '</span></td>
                    <td><span class="text-dark fw-bold d-block fs-6">' . $permissions . '</span></td>
                    <td>
                        <div class="d-flex gap-3 align-items-center">
                            <button type="button" class="btn btn-sm btn-light-primary editRoleButton"
                                data-bs-toggle="modal" data-bs-target="#editRoleModal"
                                data-id="' . encrypt($rd->id) . '"
                                data-role-name="' . $rd->role_name . '"
                                data-permissions="' . $permissions . '"
                                data-url="' . route('role.update', encrypt($rd->id)) . '">Edit</button>
                            ' . $deleteButton . '
                        </div>
                    </td>
                </tr>';
        }

        if ($roles->isEmpty()) {
            $html = '<tr><td colspan="3" class="text-center text-muted">No search results found</td></tr>';
        }

        return response()->json([
            'html' => $html,
            'pagination' => $roles->appends([
                'query' => $query,
                'sort_column' => $sortColumn,
                'sort_direction' => $sortDirection
            ])->links()->render(),
            'count' => $roles->total(),
        ]);
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