<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Config;
use DB;
use Illuminate\Http\Request;

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
        $data = UserModel::all();
        return view('User', ['data' => $data]);
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
        UserModel::insert([
            'name' => $request->input('name'),
            'age' => $request->input('age'),
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
        return view('UserEdit', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        UserModel::where('id' , '=' , $id)->update([
            'name' => $request->input('name'),
            'age' => $request->input('age'),
        ]);
        return redirect()->route('user.index');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        UserModel::where('id', '=', $id)->delete();
        return redirect()->route('user.index');
    }
}
