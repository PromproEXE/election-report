<?php

use App\Http\Controllers\PartyController;
use App\Http\Controllers\ScoreController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('score')
    ->controller(ScoreController::class)
    ->group(function () {
        Route::get('/', 'getAll')->name('getAll');
        Route::get('/{classes}', 'getByClasses')->name('getByClasses');
    });
Route::prefix('party')
    ->controller(PartyController::class)
    ->group(function () {
        Route::get('/', 'getAll')->name('party-get');
        Route::post('/create', 'create')->name('party-create');
        Route::put('/{id}/update', 'update')->name('party-update');
        Route::delete('/{id}/delete', 'delete')->name('party-delete');
    });
