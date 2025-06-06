<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::resource('superAdmin' , SuperAdminController::class);

// Route::get('/key', function () {
//     if (session()->has('access_granted')) {
//         return redirect()->route('adminPage');
//     }
//     return view('Key');
// });
Route::view('/key' , 'Key');
Route::post('key', [AdminController::class , 'key'])->name('key');

Route::get('admin', [AdminController::class , 'adminPage'])->name('adminPage');
Route::get('UserTable', [AdminController::class , 'UserTable'])->name('UserTable');
Route::post('admin', [AdminController::class , 'admin'])->name('admin');

// Route::redirect('superAdmin' , 'key');

Route::get('dashboard', [AdminController::class , 'dashboardPage'])->name('dashboard');
Route::post('dashboard', [AdminController::class , 'UserCrudInstall'])->name('UserCrudInstall');
Route::any('roleinstall', [AdminController::class , 'roleInstall'])->name('roleInstall');

Route::resource('role' , RoleController::class);
Route::resource('user' , UserController::class);
Route::put('admin_update' , [UserController::class , 'admin_update'])->name('admin_update');
Route::post('UserTableEdit' , [UserController::class , 'User_Table_edit'])->name('User_Table_edit');


Route::post('logout', [AdminController::class , 'logout'])->name('logout');
