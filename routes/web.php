<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

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


Route::controller(AuthController::class)->group(function () {
    Route::get('/signin', 'UserLogin');
    Route::post('/signin', 'Login');
    Route::get('/signup', 'UserAuth');
    Route::post('/signup', 'Registration');
    Route::get('/', 'Main');
    Route::get('/exit', 'Exit');
    Route::get('/create/receipt', 'CreateReceipt');
    Route::post('/create/receipt', 'UploadImage');
});

Route::fallback(function () {
    return redirect('/');
});
