<?php

use Illuminate\Http\Request;
use App\Http\Middleware\CheckToken;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\PresensiController;

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', [LoginController::class, 'user']);
    // Route::post('/pegawai/radius', [PresensiController::class, 'storeRadius']);
    // Route::get('/pegawai/radius', [PresensiController::class, 'radius']);
    Route::get('/pegawai/presensi/seminggu', [PresensiController::class, 'presensiSeminggu']);
    Route::get('/pegawai/presensi/lokasi', [PresensiController::class, 'lokasiAbsen']);
    Route::post('/pegawai/presensi/masuk', [PresensiController::class, 'absenMasuk']);
    Route::post('/pegawai/presensi/pulang', [PresensiController::class, 'absenPulang']);
});

Route::post('/login', [LoginController::class, 'login']);
