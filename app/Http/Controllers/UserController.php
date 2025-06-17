<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use App\Models\UserModel;
use Config;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
            $role = RoleModel::get('role_name');
            $data = UserModel::paginate(5);

            return view('User', ['data' => $data, 'role' => $role]);
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Assign the role First');
        }
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
            'name' => ['required', 'min:2'],
            'email' => ['required', 'min:2', 'email'],
            'password' => ['required', 'min:2', 'max:8'],
            'role' => ['required']
        ]);
        UserModel::insert([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'role' => $request->input('role'),
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
            'role' => $request->input('role'),
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
