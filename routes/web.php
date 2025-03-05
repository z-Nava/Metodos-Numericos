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
})->name('welcome');

Route::get('/euler-method', [MethodsController::class, 'indexE'])->name('euler-method');
Route::post('/euler-method', [MethodsController::class, 'calculateEuler'])->name('calculate-euler');

Route::get('/runge-kutta-method', [MethodsController::class, 'indexK'])->name('kutta-method');
Route::post('/runge-kutta-method', [MethodsController::class, 'calculateKutta'])->name('calculate-kutta');

Route::get('/newton-method', [MethodsController::class, 'indexN'])->name('newton-method');
Route::post('/newton-method', [MethodsController::class, 'calculateNewton'])->name('calculate-newton');

