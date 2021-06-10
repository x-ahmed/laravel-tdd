<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

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

Route::patch('/book/{book}', [BookController::class, 'update']);
Route::post('/book', [BookController::class, 'store']);

Route::get('/', function () {
    return view('welcome');
});
