<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if (env('MAINTENCE') == true) {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home'); //ha un middleware auth nella classe
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home'); //ha un middleware auth nella classe

    Auth::routes();

    Route::prefix('admin')->group(function () {
        Route::get('dashboard', [App\Http\Controllers\AdminDashboardController::class, 'index'])->middleware(['admin'])->name('admin-dashboard');
        Route::get('login', [App\Http\Controllers\AdminDashboardController::class, 'login'])->name('admin-login');
    });

}
