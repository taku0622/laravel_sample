<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SampleController;
use App\Http\Controllers\SampleFormController;
use App\Http\Controllers\LineBotController;

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

Route::get('/form/index', [SampleFormController::class, 'index']);
// Route::get('/form/index', 'SampleFormController@index');

Route::get('/form/show/{id}', [SampleFormController::class, 'show']);

Route::post('/form/store', [SampleFormController::class, 'store']);
// Route::post('/form/store', 'SampleFormController@store');

Route::post('/form/delete', [SampleFormController::class, 'delete']);
// Route::post('/form/delete', 'SampleFormController@delete');

Route::post('/form/update', [SampleFormController::class, 'update']);
// Route::post('/form/update', 'SampleFormController@update');

Route::get('/hello', [LineBotController::class, 'index']);
// Route::get('/hello', 'LineBotController@index');

Route::post('/parrot', [LineBotController::class, 'parrot']);
