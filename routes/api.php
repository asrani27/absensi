<?php

use Illuminate\Http\Request;
use App\Http\Middleware\CheckToken;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\PresensiController;

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::get('/user', [LoginController::class, 'user']);
    Route::post('/pegawai/radius', [PresensiController::class, 'storeRadius']);
    Route::get('/pegawai/radius', [PresensiController::class, 'radius']);
});

Route::post('/login', [LoginController::class, 'login']);