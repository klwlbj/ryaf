<?php

use App\Http\Controllers\Controller;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', [Controller::class, 'test']);

Route::get('/error', [Controller::class, 'error']);

Route::get('/realPlay/{simNo}/{channel}', [Controller::class, 'realPlay']);

Route::get('/playBack/{simNo}/{channel}', [Controller::class, 'playBack']);
