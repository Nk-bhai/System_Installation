<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use App\Models\UserModel;
use Artisan;
use Config;
use DB;
use Http;
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
        $key = $request->input('key');

        $response = Http::get('https://jsonplaceholder.typicode.com/posts/1');
        // $response = Http::get('http://127.0.0.1:8000/api/superadmin/1');
        // return $response['title'];
        // dd("HELLO");

        if ($key !== '1234') {
            return redirect()->back()->with('error', 'Key Not Valid');
        }
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
    //     $login_data = UserModel::where('email', '=', $email)->first();
    //     // $login_data = UserModel::get();

    //     // i provided credentials
    //     if ($email == 'nk@gmail.com' && $password == 'Nk@12345') {
    //         session(['email' => $email]);
    //         return redirect()->route('dashboard');
    //     } else {

    //         if ($email == $login_data->email && $password == $login_data->password) {
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

        // Login via hardcoded super admin OR static db user table
        if ($email === 'nk@gmail.com' && $password === 'Nk@12345') {
            session(['email' => $email]);
            return redirect()->route('dashboard');
        }

        $user = UserModel::where('email', $email)->first();

        if ($user && $user->password === $password) {
            session(['login_email' => $email]);
            return redirect()->route('UserTable');
        } else {
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
            '--database' => 'nk_db',
            '--path' => 'database/migrations/2025_06_04_093250_user_crud.php',
        ]);

        return redirect()->route('user.index');
    }

    public function roleInstall()
    {
        Artisan::call('migrate', [
            '--database' => 'nk_db',
            '--path' => 'database/migrations/2025_06_05_112216_role.php',
        ]);

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
        $request->session()->flush();
        return redirect()->route('adminPage');
    }
}
