<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JamController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\QrcodeController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\GenerateController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\LiburNasionalController;
use App\Http\Controllers\JenisKeteranganController;

Route::get('/', function(){
    return view('welcome');
});

Route::get('/logout', function(){
    Auth::logout();
    return redirect('/');
});

Route::get('/login', function(){
    return view('welcome');
})->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::group(['middleware' => ['auth', 'role:pegawai']], function () {
    Route::get('/pegawai/presensi/masuk', [PresensiController::class, 'masuk']);
    Route::post('/pegawai/presensi/masuk', [PresensiController::class, 'storeMasuk']);
    Route::get('/pegawai/presensi/pulang', [PresensiController::class, 'pulang']);
    Route::prefix('pegawai/presensi')->group(function () {
        Route::get('radius', [PresensiController::class, 'radius']);
        Route::post('radius', [PresensiController::class, 'storeRadius']);
        Route::get('barcode', [PresensiController::class, 'barcode']);
        Route::get('barcode/front', [PresensiController::class, 'frontCamera']);
        Route::get('barcode/back', [PresensiController::class, 'backCamera']);
        Route::post('barcode/scan', [PresensiController::class, 'scanBarcode']);
        Route::get('manual ', [PresensiController::class, 'manual']);
        Route::post('manual', [PresensiController::class, 'storeManual']);
        Route::get('history', [HistoryController::class, 'index']);
        Route::get('history/search', [HistoryController::class, 'search']);
    });
});

Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::post('/admin/updatelocation', [AdminController::class, 'updatelocation']);
    Route::prefix('admin')->group(function () {
        Route::resource('lokasi', LokasiController::class);
        Route::get('pegawai/sync', [PegawaiController::class, 'sync']);
        Route::get('pegawai/createuser', [PegawaiController::class, 'createuser']);
        Route::get('pegawai/{id}/resetpass', [PegawaiController::class, 'resetpass']);
        Route::get('pegawai/{id}/lokasi', [PegawaiController::class, 'lokasi']);
        Route::get('pegawai/{id}/editlokasi', [PegawaiController::class, 'editlokasi']);
        Route::post('pegawai/{id}/editlokasi', [PegawaiController::class, 'updateLokasi']);
        Route::post('pegawai/{id}/lokasi', [PegawaiController::class, 'storeLokasi']);
        Route::resource('pegawai', PegawaiController::class);
        Route::get('qrcode/generate', [QrcodeController::class, 'generateQrcode']);
        Route::get('qrcode/tampil/{id}', [QrcodeController::class, 'tampilQr']);
        Route::resource('qrcode', QrcodeController::class);
        Route::resource('cuti', CutiController::class);
    });
});


Route::group(['middleware' => ['auth', 'role:superadmin']], function () {
    Route::prefix('superadmin')->group(function () {
        Route::resource('jam', JamController::class);
        Route::resource('libur', LiburNasionalController::class);
        Route::resource('jenis', JenisKeteranganController::class);
        Route::get('generatetanggal', [GenerateController::class, 'index']);
        Route::get('generatetanggal/{bulan}', [GenerateController::class, 'generate']);
    });
});


Route::group(['middleware' => ['auth', 'role:superadmin|admin|pegawai']], function () {
    Route::get('/home/superadmin', [HomeController::class, 'superadmin']);
    Route::get('/home/admin', [HomeController::class, 'admin']);
    Route::get('/home/pegawai', [HomeController::class, 'pegawai']);
});