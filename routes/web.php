<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'account'], function () {

    //guest middleware(before login) for User
    Route::group(['middleware' => 'guest'], function () {

        Route::get('login', [LoginController::class, 'index'])->name('account.login');
        Route::post('process_register', [LoginController::class, 'processRegister'])->name('account.processRegister');
        Route::get('register', [LoginController::class, 'register'])->name('account.register');
        Route::post('authenticate', [LoginController::class, 'authenticate'])->name('account.authenticate');
    });

    //Authenticated middleware(after login) for User
    Route::group(['middleware' => 'auth'], function () {
        Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('account.dashboard');
        Route::get('logout', [LoginController::class, 'logout'])->name('account.logout');
    });
});

//Admin Routes
Route::group(['prefix' => 'admin'], function () {

    //guest middleware(before login) for Admin
    Route::group(['middleware' => 'admin.guest'], function () {
        Route::get('login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });

    //Authenticated middleware(after login) for Admin
    Route::group(['middleware' => 'admin.auth'], function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
    });
});
