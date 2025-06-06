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

    private function setDynamicDatabaseConnection($email)
    {
        $emailArray = explode('@', $email);
        $db_prefix = $emailArray[0];
        $databasename = $db_prefix . '_DB';

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

        return $databasename;
    }

    public function __construct()
    {
        if (session()->has('email')) {
            $this->setDynamicDatabaseConnection(session('email'));
        }

    }

    public function key(Request $request)
    {
        $key = $request->input('key');
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



    public function admin(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        // i provided credentials
        if ($email == 'nk@gmail.com' && $password == 'nk@1234') {
            session(['email' => $email]);
            return redirect()->route('dashboard');
        } else {

            $login_data = UserModel::where('email', '=', $email)->first();

            if ($email == $login_data->email && $password == $login_data->password) {
                session(['login_email' => $email]);
                return redirect()->route('UserTable');
            } else {
                return redirect()->back()->with('error', 'Invalid Credentials');
            }

        }
    }


    public function dashboardPage()
    {
        // dump(session('email'));
        return view('Dashboard');
    }
    public function UserCrudInstall()
    {
        $email = session('email');
        if (!empty($email)) {
            $databasename = $this->setDynamicDatabaseConnection($email);

            Artisan::call('migrate', [
                '--database' => $databasename,
                '--path' => 'database/migrations/2025_06_04_093250_user_crud.php',
            ]);

            return redirect()->route('user.index');
        }

        return redirect()->route('dashboard');
    }

    public function roleInstall()
    {
        $email = session('email');
        if (!empty($email)) {
            $databasename = $this->setDynamicDatabaseConnection($email);

            Artisan::call('migrate', [
                '--database' => $databasename,
                '--path' => 'database/migrations/2025_06_05_112216_role.php',
            ]);

            return redirect()->route('role.index');
        }

        return redirect()->route('dashboard');
    }



    public function UserTable()
    {
        $data = UserModel::where('email' , '!=' , session('login_email'))->get();
        $user_name = UserModel::where('email' , '=' , session('login_email'))->get('name');
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

        return view('UserTable', ['user_name' => $user_name ,'data' => $data, 'permissions' => $permissions]);
    }


    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('adminPage');
    }
}
