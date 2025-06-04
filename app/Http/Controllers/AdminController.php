<?php

namespace App\Http\Controllers;

use Artisan;
use Config;
use DB;
use Illuminate\Http\Request;

class AdminController extends Controller
{


    public function key(Request $request)
    {
        $key = $request->input('key');
        if ($key !== '1234') {
            return redirect()->back()->with('error', 'Key Not Valid');
        } else {
            return redirect()->route('adminPage');
        }
    }


    public function adminPage()
    {
        return view('Admin');
    }

    public function admin(Request $request)
    {
        $email = $request->input('email');
        session(['email' => $email]);
        $password = $request->input('password');
        if ($email == 'nk@gmail.com' && $password == 'nk@1234') {

            return redirect()->route('dashboard');
        } else {
            return redirect()->back()->with('error', 'Invalid Credentials');
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

            $emailArray = explode('@', $email);
            $db_prefix = $emailArray[0];
            $databasename = $db_prefix . '_DB';
            // dd($databasename);

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

            Artisan::call('migrate', [
                '--database' => $databasename,
                '--path' => 'database/migrations/2025_06_04_093250_user_crud.php',
            ]);

        
            DB::setDefaultConnection($databasename);
            return redirect()->route('user.index');
        } else {
            return redirect()->route('dashboard');
        }



    }


    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('adminPage');
    }
}
