<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ChangePasswordController;

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

@include_once('admin_web.php');
Route::group(['middleware' => 'auth'], function () {

    Route::get('/', function () {
        return redirect()->route('index');
    })->name('/');

    Route::view('sample-page', 'admin.pages.sample-page')->name('sample-page');

    Route::prefix('dashboard')->group(function () {
        Route::view('/', 'admin.dashboard.default')->name('index');
        Route::view('default', 'admin.dashboard.default')->name('dashboard.index');
    });

    Route::view('default-layout', 'multiple.default-layout')->name('default-layout');
    Route::view('compact-layout', 'multiple.compact-layout')->name('compact-layout');
    Route::view('modern-layout', 'multiple.modern-layout')->name('modern-layout');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/change-password', [ChangePasswordController::class, 'showChangePasswordForm'])
    ->name('password.change.form');
    Route::post('/change-password', [ChangePasswordController::class, 'changePassword'])
    ->name('password.change');
    // Dans routes/web.php
    Route::post('/skip-password-change', [ChangePasswordController::class, 'skipPasswordChange'])
    ->name('password.skip');

});
Auth::routes();

