<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SampleController;

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

Route::get('/test', function () {
    return view('welcome');
});

// Route::get('/sample', 'SampleController@index');
Route::get('/sample', [SampleController::class, 'index']);

Route::get('/select', [SampleController::class, 'select']);

Route::get('/select_many', [SampleController::class, 'selectMany']);

Route::get('/insert', [SampleController::class, 'insert']);

Route::get('/delete', [SampleController::class, 'delete']);

Route::get('/update', [SampleController::class, 'update']);
