<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\DML_API\InsertController as DMLInsert;
use App\Http\Controllers\Multi_API\ColumnController as ColumnFactory;

######################### Public Route #########################

Route::post('/v1/{type}/{db}/{method}/{len}', [DMLInsert::class, 'insertQuery']);

Route::prefix('/v2/column')->group(function() {
    Route::get('/factory/{id}', [ColumnFactory::class, 'getFactoryByIdType']);
});
