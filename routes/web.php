<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Nk\SystemAuth\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});



// Route::view('/key', 'Key')->name('key');
// Route::post('key', [AdminController::class, 'key'])->name('key');
// Route::get('admin', [AdminController::class, 'adminPage'])->name('adminPage');
Route::post('admin', [AdminController::class, 'admin'])->name('admin');

Route::get('UserTable', [AdminController::class, 'UserTable'])->name('UserTable');
Route::get('dashboard', [AdminController::class, 'dashboardPage'])->name('dashboard');
Route::get('UserCrudInstall', [AdminController::class, 'UserCrudInstall'])->name('UserCrudInstall');
Route::any('roleinstall', [AdminController::class, 'roleInstall'])->name('roleInstall');
Route::post('logout', [AdminController::class, 'logout'])->name('logout');

Route::resource('role', RoleController::class);

Route::resource('user', UserController::class);
Route::put('admin_update', [UserController::class, 'admin_update'])->name('admin_update');
Route::post('UserTableEdit', [UserController::class, 'User_Table_edit'])->name('User_Table_edit');