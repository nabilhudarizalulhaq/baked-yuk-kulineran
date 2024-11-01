<?php

use App\Http\Controllers\ApiFavoriteController;
use App\Http\Controllers\ApiGuestController;
use App\Http\Controllers\ApiWisataKulinerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WisataKulinerController;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/wisata-kuliner', [ApiGuestController::class, 'fetchWisataKuliner']);
Route::middleware('auth:sanctum')->get('/kategori', [ApiGuestController::class, 'fetchKategori']);
Route::middleware('auth:sanctum')->post('/favorite/{id_user}', [ApiFavoriteController::class, 'fetchFavorite']);
Route::middleware('auth:sanctum')->post('/add-favorite', [ApiFavoriteController::class, 'addFavorite']);
// tambahkan route baru
// bikin kontroller baru diawali Apixxxxx

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
