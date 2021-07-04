<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PresensiController;

Route::get('/', function(){
    return view('welcome');
});
Route::post('/login', [LoginController::class, 'login']);

Route::group(['middleware' => ['auth', 'role:pegawai']], function () {
    Route::get('/home/pegawai', [HomeController::class, 'pegawai']);
    Route::get('/pegawai/presensi/masuk', [PresensiController::class, 'masuk']);
    Route::get('/pegawai/presensi/pulang', [PresensiController::class, 'pulang']);
});