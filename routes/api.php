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
    Route::get('/checkversion/{version}', [PresensiController::class, 'checkVersion']);
    Route::get('/user', [LoginController::class, 'user']);
    Route::get('/profil', [PresensiController::class, 'profil']);
    Route::get('/history/{bulan}/{tahun}', [PresensiController::class, 'history']);
    Route::post('/gantipass', [LoginController::class, 'gantipass']);

    Route::get('/pegawai/presensi/seminggu', [PresensiController::class, 'presensiSeminggu']);
    Route::get('/pegawai/presensi/newseminggu', [PresensiController::class, 'newPresensiSeminggu']);
    Route::get('/pegawai/presensi/today', [PresensiController::class, 'presensiToday']);
    Route::get('/pegawai/presensi/lokasi', [PresensiController::class, 'lokasiAbsen']);
    Route::post('/pegawai/presensi/masuk', [PresensiController::class, 'absenMasuk']);
    Route::post('/pegawai/presensi/pulang', [PresensiController::class, 'absenPulang']);
});

Route::post('/login', [LoginController::class, 'login']);
Route::post('/newlogin', [LoginController::class, 'newlogin']);
Route::get('/testing', [LoginController::class, 'testing']);
