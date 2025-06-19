<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use App\Models\UserModel;
use Artisan;
use Config;
use DB;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;


class AdminController extends Controller
{

    public function __construct(Request $request)
    {
        // dd(session('database_name'));
        if (!empty(session('database_name'))) {
            $databasename = session('database_name');
        } else {
            $ip_address = $request->ip();
            $response = Http::get("http://192.168.12.79:8005/api/superadmin/get/{$ip_address}");
            $keyData = $response->json();
            if ($keyData[$ip_address] == $ip_address && $keyData['verified'] == 1) {
                $databasename = $keyData['database'];
                session(['superadmin_profile_logo' => $keyData['profile_logo']]);
                session(['superadmin_email' => $keyData['email']]);
                session(['superadmin_password' => $keyData['password']]);
            }

        }



        // $databasename = "hello";



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

    public function admin(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        try {
            $user = UserModel::where('email', $email)->first();

            if (!$user || !$user->password === $password) {
                return false; // Authentication failed
            }

            // Authentication successful, set session and return true
            session(['login_email' => $email]);
            return true;

        } catch (\Illuminate\Database\QueryException $e) {
            return false; // Database error, treat as authentication failure
        }
    }


    public function dashboardPage(Request $request)
    {
        $ip_address = $request->ip();
        $response = Http::get("http://192.168.12.79:8005/api/superadmin/get/{$ip_address}");
        $keyData = $response->json();
        // if ($keyData[$ip_address] == $ip_address && $keyData['verified'] == 1) {
        // dd($keyData);
        session(['superadmin_profile_logo' => $keyData['profile_logo']]);
        session(['superadmin_email' => $keyData['email']]);
        // }
        // dump(session('superadmin_profile_logo'));
        // dump(session('superadmin_email'));
        try {
            $roleCount = RoleModel::count();
        } catch (\Exception $e) {
            $roleCount = 0;
        }

        try {
            $userCount = UserModel::count();
        } catch (\Exception $e) {
            $userCount = 0;
        }
        // $roleCount = RoleModel::count();
        // $userCount = UserModel::count();

        // Pass the counts to the Dashboard view
        return view('Dashboard', [
            'roleCount' => $roleCount,
            'userCount' => $userCount,
            'profile_logo' => $keyData['profile_logo']
        ]);
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
        // dd("Hello");
        Artisan::call('migrate', [
            '--database' => session('dynamic_db'),
            '--path' => 'database/migrations/2025_06_05_112216_role.php',
        ]);
        // dd("Hello");

        return redirect()->route('role.index');
    }

    public function UserTable()
    {
        $data = UserModel::where('email', '!=', session('login_email'))->paginate(5); // 5 users per page (adjust as needed)
        $user_name = UserModel::where('email', '=', session('login_email'))->get('name');
        $user = UserModel::where('email', session('login_email'))->first();
        $allrole = RoleModel::get('role_name');

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        $roleName = $user->role;

        $role = RoleModel::where('role_name', $roleName)->first();

        $permissions = [];

        if ($role && $role->permissions) {
            $permissions = array_map('trim', explode(',', $role->permissions));
        }

        return view('UserTable', ['user_name' => $user_name, 'data' => $data, 'permissions' => $permissions, 'allrole' => $allrole]);
    }

    public function logout(Request $request)
    {
        if (Schema::hasTable('user')) {
        $user = UserModel::where('email', session('login_email'))->first();

        if ($user) {
            $user->last_logout_at = now();
            $user->save();
        }
        $request->session()->forget('login_email');
        $request->session()->forget('user_logged_in');
    }
        return redirect()->route('system.auth.login')->with('message', 'Logged out successfully');
    }
}


