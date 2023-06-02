<?php

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

use App\Http\Controllers\Pages\HomepageController;
use App\Http\Controllers\Pages\InsertController;

######################### Public Route #########################

Route::prefix('/')->group(function () {
    Route::get('/', [HomepageController::class, 'index'])->name('homepage');
    Route::post('/open/{menu}', [HomepageController::class, 'open']);
});

Route::prefix('/insert')->group(function () {
    Route::get('/', [InsertController::class, 'index'])->name('insert');
});
