<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MethodsController;

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

Route::get('/euler-method', [MethodsController::class, 'indexE'])->name('euler-method');
Route::post('/euler-method', [MethodsController::class, 'calculateEuler'])->name('calculate-euler');

Route::get('/runge-kutta-method', [MethodsController::class, 'indexK'])->name('kutta-method');

