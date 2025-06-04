<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::resource('superAdmin' , SuperAdminController::class);

Route::view('/key' , 'Key');
Route::post('key', [AdminController::class , 'key'])->name('key');

Route::get('admin', [AdminController::class , 'adminPage'])->name('adminPage');
Route::post('admin', [AdminController::class , 'admin'])->name('admin');

Route::redirect('superAdmin' , 'key');

Route::get('dashboard', [AdminController::class , 'dashboardPage'])->name('dashboard');
Route::post('dashboard', [AdminController::class , 'UserCrudInstall'])->name('UserCrudInstall');

Route::resource('user' , UserController::class);


Route::post('logout', [AdminController::class , 'logout'])->name('logout');

route::view('table' , 'table');