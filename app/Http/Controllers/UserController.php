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

        $users = UserModel::where('name', 'like', "%{$query}%")
        ->join('role', 'user.role_id', '=', 'role.id') // Join with roles
        ->where('user.name', 'like', "%{$query}%")
        ->orWhere('user.email', 'like', "%{$query}%")
        ->orWhere('role.role_name', 'like', "%{$query}%") // Search role name from roles table
        ->with('role')
            ->paginate(5);

        // Render the HTML manually (blade in controller)
        $html = '';
        foreach ($users as $dt) {
            $html .= '
            <tr>
                <td><span class="text-dark fw-bold d-block fs-6">' . $dt->name . '</span></td>
                <td><span class="text-dark fw-bold d-block">' . $dt->email . '</span></td>
                <td><span class="text-dark fw-bold d-block">' . $dt->role->role_name . '</span></td>
                <td><span class="text-dark fw-bold d-block">' .
                ($dt->last_logout_at
                    ? \Carbon\Carbon::parse($dt->last_logout_at)->timezone("Asia/Kolkata")->format("d-m-Y h:i A")
                    : '-') .
                '</span></td>
                <td>
                    <div class="d-flex gap-3 align-items-center">
                        <button type="button" class="btn btn-sm btn-light-primary editUserButton"
                            data-bs-toggle="modal" data-bs-target="#editUserModal"
                            data-id="' . $dt->id . '" data-name="' . $dt->name . '"
                            data-email="' . $dt->email . '" data-role="' . $dt->role->role_name . '"
                            data-url="' . route('user.update', $dt->id) . '">Edit</button>
    
                        <button type="button" class="btn btn-sm btn-light-danger deleteUserButton"
                            data-bs-toggle="modal" data-bs-target="#deleteUserModal"
                            data-id="' . $dt->id . '" data-name="' . $dt->name . '"
                            data-url="' . route('user.destroy', $dt->id) . '">Delete</button>
                    </div>
                </td>
            </tr>';
        }

        if ($users->isEmpty()) {
            $html = '<tr><td colspan="5" class="text-center text-muted">No search result found</td></tr>';
        }

        return response()->json([
            'html' => $html,
            'pagination' => $users->appends(['query' => $query])->links()->render(),
            'count' => $users->total(),
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
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => ['required', 'min:2'],
    //         'email' => ['required', 'min:2', 'email'],
    //         'password' => ['required', 'min:2', 'max:8'],
    //         'role' => ['required']
    //     ]);
    //     $seed_email = DB::table('users')->where('email' , '=' , $request->input('email'))->value('email');



    //     if($seed_email !== $request->input('email')){
    //         $hashpassword = Hash::make($request->input('password'));
    //         UserModel::insert([
    //             'name' => $request->input('name'),
    //             'email' => $request->input('email'),
    //             'password' => $hashpassword,
    //             'role' => $request->input('role'),
    //             'created_at' => Carbon::now()
    //         ]);
    //         return redirect()->route('user.index');
    //     }else{
    //             // dd($seed_email);
    //         return redirect()->back()->with('error' , 'cannot set credentials of Super Admin');

    //     }
    // }


    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => ['required', 'min:2'],
            'email' => ['required', 'min:2', 'email'],
            'password' => ['required', 'min:2', 'max:8'],
            'role' => ['required']
        ]);

        $emailExists = DB::table('users')->where('email', $request->input('email'))->exists();

        if ($emailExists) {
            // Flash the error message
            session()->flash('errorss', 'Cannot set credentials of Super Admin');
            // Debug: Log or dump session to verify
            Log::info('Session data after flash: ', session()->all());
            return redirect()->back()->withInput();
        }

        $hashpassword = Hash::make($request->input('password'));
        UserModel::insert([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $hashpassword,
            'role_id' => $request->input('role'),
            'created_at' => Carbon::now()
        ]);

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
            'role' => $request->input('role'),
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
