<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;

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

Route::post('/author', [AuthorController::class, 'store']);

Route::delete('/book/{book}-{slug}', [BookController::class, 'destroy'])->name('book.destroy');
Route::patch('/book/{book}-{slug}', [BookController::class, 'update'])->name('book.update');
Route::post('/book', [BookController::class, 'store'])->name('book.store');
Route::get('/book/{book}-{slug}', [BookController::class, 'index'])->name('book.show');
Route::get('/book', [BookController::class, 'index'])->name('book.index');

Route::get('/', function () {
    return view('welcome');
});
