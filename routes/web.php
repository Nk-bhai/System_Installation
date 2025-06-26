<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckLogin;
use App\Http\Middleware\RestrictLoggedIn;
use Illuminate\Support\Facades\Route;
use Nk\SystemAuth\Http\Controllers\AuthController;


Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['web']], function () {

    // Routes without 'restrict.login' middleware
    Route::post('admin', [AdminController::class, 'admin'])->name('admin');
    Route::get('dashboard', [AdminController::class, 'dashboardPage'])->name('dashboard');
    Route::get('logout', [AdminController::class, 'logout'])->name('logout');
    Route::get('/usertable/search', [AdminController::class, 'search'])->name('usertable.search');
    Route::get('/usertable/data', [AdminController::class, 'getUserTableData'])->name('usertables.data');
    Route::get('UserTable', [AdminController::class, 'UserTable'])->name('UserTable');

    Route::post('user', [UserController::class, 'store'])->name('user.store');
    Route::put('user/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::put('admin_update', [UserController::class, 'admin_update'])->name('admin_update');
    Route::post('/user/check-email', [UserController::class, 'checkEmailExists'])->name('user.checkEmail');
    Route::get('users/data', [UserController::class, 'getData'])->name('users.data');
    
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');


    // Group routes that require 'restrict.login' middleware
    Route::group(['middleware' => ['restrict.login']], function () {
        Route::get('UserCrudInstall', [AdminController::class, 'UserCrudInstall'])->name('UserCrudInstall');
        Route::get('roleInstall', [AdminController::class, 'roleInstall'])->name('roleInstall');

        Route::resource('role', RoleController::class);
        Route::get('/roles/search', [RoleController::class, 'search'])->name('role.search');
        Route::post('/role/check-role-name', [RoleController::class, 'checkRoleName'])->name('role.checkRoleName');
        Route::get('roles/data', [RoleController::class, 'getData'])->name('roles.data');

        Route::get('user', [UserController::class, 'index'])->name('user.index');
        Route::get('/users/search', [UserController::class, 'search'])->name('user.search');

        Route::get('/site_control', [ProfileController::class, 'SiteControlPage'])->name('SiteControlPage');
        Route::patch('/site_control', [ProfileController::class, 'SiteControl'])->name('SiteControl');
    });


});



// Route::get('/', function () {
//     return view('welcome');
// });


// Route::group(['middleware' => ['web']], function () {
//     Route::post('admin', [AdminController::class, 'admin'])->name('admin');
//     Route::get('UserTable', [AdminController::class, 'UserTable'])->name('UserTable');
//     Route::get('dashboard', [AdminController::class, 'dashboardPage'])->name('dashboard');
//     Route::get('UserCrudInstall', [AdminController::class, 'UserCrudInstall'])->name('UserCrudInstall')->middleware('restrict.login');
//     Route::get('roleInstall', [AdminController::class, 'roleInstall'])->name('roleInstall')->middleware('restrict.login');
//     Route::get('logout', [AdminController::class, 'logout'])->name('logout');
//     Route::get('/usertable/search', [AdminController::class, 'search'])->name('usertable.search');
//     Route::get('/usertable/data', [AdminController::class, 'getUserTableData'])->name('usertables.data');



//     Route::resource('role', RoleController::class)->middleware('restrict.login');
//     Route::get('/roles/search', [RoleController::class, 'search'])->name('role.search')->middleware('restrict.login');
//     Route::post('/role/check-role-name', [RoleController::class, 'checkRoleName'])->name('role.checkRoleName')->middleware('restrict.login');
//     Route::get('roles/data', [RoleController::class, 'getData'])->name('roles.data')->middleware('restrict.login');


//     // Route::resource('user', UserController::class);
//     Route::get('user', [UserController::class, 'index'])->name('user.index')->middleware('restrict.login');
//     Route::post('user', [UserController::class, 'store'])->name('user.store');
//     Route::put('user/{id}', [UserController::class, 'update'])->name('user.update');
//     Route::delete('user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

//     Route::put('admin_update', [UserController::class, 'admin_update'])->name('admin_update');
//     Route::get('/users/search', [UserController::class, 'search'])->name('user.search')->middleware('restrict.login');
//     Route::post('/user/check-email', [UserController::class, 'checkEmailExists'])->name('user.checkEmail');
//     Route::get('users/data', [UserController::class, 'getData'])->name('users.data');



//     Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::post('/change_password', [ProfileController::class, 'PasswordUpdate'])->name('user.changepassword');
//     Route::get('/site_control', [ProfileController::class, 'SiteControlPage'])->name('SiteControlPage')->middleware('restrict.login');
//     Route::patch('/site_control', [ProfileController::class, 'SiteControl'])->name('SiteControl')->middleware('restrict.login');

// });

