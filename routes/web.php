<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\GalleryController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(LoginRegisterController::class)->group(function() {
    Route::get('/edit', 'editFoto')->name('editFoto');
    Route::get('/register', 'register')->name('register');
    Route::post('/store', 'store')->name('store');
    Route::get('/login', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::post('/logout', 'logout')->name('logout');
    Route::post('/update-foto', 'updateFoto')->name('updateFoto');
    Route::get('/resize-foto', 'resizeFoto')->name('resizeFoto');
    Route::post('/update-size-foto', 'updateSizeFoto')->name('updateSizeFoto');
   });
Route::get('/home', function(){
    return view('welcome');
});
Route::resource('gallery', GalleryController::class);
