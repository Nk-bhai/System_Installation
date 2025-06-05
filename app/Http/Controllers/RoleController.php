<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use Config;
use DB;
use Illuminate\Http\Request;

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
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd("HI");
        return view('Role');
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
        $role_name = $request->input('role');
        $permissions = $request->input('permissions');

        $permissions_string = "";  
        foreach($permissions as $p){
            $permissions_string .= $p . ','; 
        } 
        // echo $permissions_string;exit;
        // print_r($permissions);exit;
        RoleModel::insert([
            'role_name' => $role_name,
            'permissions' => $permissions_string
        ]);
        return redirect()->route('role.index');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
