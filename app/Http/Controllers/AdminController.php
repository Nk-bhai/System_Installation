<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use App\Models\UserModel;
use Artisan;
use Config;
use DB;
use Illuminate\Http\Request;


class AdminController extends Controller
{

    public function __construct()
    {
        $databasename = 'nk_db';
        DB::statement('CREATE DATABASE IF NOT EXISTS ' . $databasename);

        Config::set('database.connections.' . $databasename, [
            'driver' => 'mysql',
            'host' => config('database.connections.mysql.host'),
            'port' => config('database.connections.mysql.port'),
            'database' => $databasename,
            'username' => config('database.connections.mysql.username'),
            'password' => config('database.connections.mysql.password'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);

        session(['dynamic_db' => $databasename]);
        DB::setDefaultConnection($databasename);

    }

    public function key(Request $request)
    {
        $request->validate([
            'key' => ['required']
        ]);
        $key = $request->input('key');

        // if ($key !== '1234') {
        //     return redirect()->back()->with('error', 'Key Not Valid');
        // }

        session(['access_granted' => true]);
        return redirect()->route('adminPage');

    }

    public function adminPage()
    {
        return view('Admin');
    }


    // public function admin(Request $request)
    // {
    //     $email = $request->input('email');
    //     $password = $request->input('password');

    //     // i provided credentials
    //     if ($email === 'nk@gmail.com' && $password === 'Nk@12345') {
    //         session(['email' => $email]);
    //         return redirect()->route('dashboard');
    //     } else {
    //         $user = UserModel::where('email', '=', $email)->first();

    //         if (!$user) {
    //             return redirect()->back()->with('error', 'Invalid Credentials');
    //         }

    //         if ($user && $user->password === $password) {
    //             session(['login_email' => $email]);
    //             return redirect()->route('UserTable');
    //         } else {
    //             return redirect()->back()->with('error', 'Invalid Credentials');
    //         }
    //     }
    // }


    public function admin(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        // // Hardcoded credentials check
        // if ($email === 'nk@gmail.com' && $password === 'Nk@12345') {
        //     session(['email' => $email]);
        //     return redirect()->route('dashboard');
        // }

        try {
            $user = UserModel::where('email', '=', $email)->first();

            if (!$user) {
                return redirect()->back()->with('error', 'Invalid Credentials');
            }
            if ($user && $user->password === $password) {
                session(['login_email' => $email]);
                return redirect()->route('UserTable');
            } else {
                return redirect()->back()->with('error', 'Invalid Credentials');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Invalid Credentials');
        }
    }

    public function dashboardPage()
    {
        return view('Dashboard');
    }

    public function UserCrudInstall()
    {
        Artisan::call('migrate', [
            '--database' => session('dynamic_db'),
            '--path' => 'database/migrations/2025_06_04_093250_user_crud.php',
        ]);

        return redirect()->route('user.index');
    }

    public function roleInstall()
    {
        Artisan::call('migrate', [
            '--database' => session('dynamic_db'),
            '--path' => 'database/migrations/2025_06_05_112216_role.php',
        ]);
        // dd("Hello");

        return redirect()->route('role.index');
    }

    public function UserTable()
    {
        $data = UserModel::where('email', '!=', session('login_email'))->get();
        $user_name = UserModel::where('email', '=', session('login_email'))->get('name');
        $user = UserModel::where('email', session('login_email'))->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        $roleName = $user->role;

        $role = RoleModel::where('role_name', $roleName)->first();

        $permissions = [];

        if ($role && $role->permissions) {
            $permissions = array_map('trim', explode(',', $role->permissions));
        }

        return view('UserTable', ['user_name' => $user_name, 'data' => $data, 'permissions' => $permissions]);
    }

    public function logout(Request $request)
    {
        // $request->session()->forgot('login_email');
        return redirect()->route('adminPage');
    }
}


