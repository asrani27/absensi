<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JamController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SkpdController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\QrcodeController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\RentangController;
use App\Http\Controllers\GenerateController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\PuskesmasController;
use App\Http\Controllers\RingkasanController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\LaporanAdminController;
use App\Http\Controllers\LiburNasionalController;
use App\Http\Controllers\JenisKeteranganController;

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->hasRole('pegawai')) {
            return redirect('/home/pegawai');
        } elseif (Auth::user()->hasRole('admin')) {
            return redirect('/home/admin');
        } elseif (Auth::user()->hasRole('superadmin')) {
            return redirect('/home/superadmin');
        }
    }
    return view('welcome');
});

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
});

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect('/');
    }
    return view('welcome');
})->name('login');

Route::post('/login', [LoginController::class, 'login']);

Route::group(['middleware' => ['auth', 'role:pegawai']], function () {
    Route::get('/pegawai/presensi/masuk', [PresensiController::class, 'masuk']);
    Route::get('/pegawai/gantipass', [PresensiController::class, 'gantipassword']);
    Route::post('/pegawai/gantipass', [PresensiController::class, 'updatepassword']);
    Route::post('/pegawai/lokasi', [PresensiController::class, 'storeLokasi']);
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
        Route::get('testing ', [PresensiController::class, 'testing']);
        Route::post('manual', [PresensiController::class, 'storeManual']);
        Route::get('history', [HistoryController::class, 'index']);
        Route::get('history/search', [HistoryController::class, 'search']);

        Route::get('pagi', [PresensiController::class, 'pagi']);
        Route::post('pagi', [PresensiController::class, 'simpanpagi']);
        Route::get('siang', [PresensiController::class, 'siang']);
        Route::post('siang', [PresensiController::class, 'simpansiang']);
        Route::get('malam', [PresensiController::class, 'malam']);
        Route::post('malam', [PresensiController::class, 'simpanmalam']);
        Route::get('malam/pulang/{id}', [PresensiController::class, 'malam_pulang']);
        Route::get('malam/masuk/{id}', [PresensiController::class, 'malam_masuk']);
    });
    Route::post('savephoto ', [PresensiController::class, 'savephoto']);
});

Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::post('/admin/updatelocation', [AdminController::class, 'updatelocation']);
    Route::prefix('admin')->group(function () {
        Route::resource('lokasi', LokasiController::class);
        Route::get('gantipass', [AdminController::class, 'gantipassword']);
        Route::get('puskesmas', [PuskesmasController::class, 'index']);
        Route::get('puskesmas/{id}/createuser', [PuskesmasController::class, 'createuser']);
        Route::get('puskesmas/{id}/resetpass', [PuskesmasController::class, 'resetpass']);
        Route::get('puskesmas/sync', [PuskesmasController::class, 'sync']);
        Route::post('gantipass', [AdminController::class, 'updatepassword']);
        Route::get('presensi/{id}', [AdminController::class, 'editPresensi']);
        Route::post('presensi/{id}', [AdminController::class, 'updatePresensi']);
        Route::get('presensi/{id}/delete', [AdminController::class, 'deletePresensi']);
        Route::get('pegawai/search', [PegawaiController::class, 'search']);
        // Route::get('pegawai/presensi/{id}/edit', [PegawaiController::class, 'editPresensi']);
        // Route::post('pegawai/presensi/{id}/edit', [PegawaiController::class, 'updatePresensi']);
        Route::get('pegawai/sync', [PegawaiController::class, 'sync']);
        Route::get('pegawai/createuser', [PegawaiController::class, 'createuser']);
        Route::get('pegawai/puskesmas', [PegawaiController::class, 'pegawaiPuskesmas']);
        Route::get('pegawai/sortir', [PegawaiController::class, 'sortir']);
        Route::post('pegawai/sortir', [PegawaiController::class, 'simpanSortir']);
        Route::get('pegawai/{id}/resetpass', [PegawaiController::class, 'resetpass']);
        Route::get('pegawai/{id}/lokasi', [PegawaiController::class, 'lokasi']);
        Route::get('pegawai/{id}/jenispresensi', [PegawaiController::class, 'jenispresensi']);
        Route::post('pegawai/{id}/jenispresensi', [PegawaiController::class, 'simpanjenispresensi']);
        Route::get('pegawai/{id}/editlokasi', [PegawaiController::class, 'editlokasi']);
        Route::post('pegawai/{id}/editlokasi', [PegawaiController::class, 'updateLokasi']);
        Route::post('pegawai/{id}/lokasi', [PegawaiController::class, 'storeLokasi']);
        Route::get('pegawai/{id}/presensi', [PegawaiController::class, 'presensi']);
        Route::get('pegawai/{id}/presensi/generate/{bulan}/{tahun}', [PegawaiController::class, 'generateTanggal']);
        Route::get('pegawai/{id}/presensi/{bulan}/{tahun}', [PegawaiController::class, 'detailPresensi']);
        Route::get('pegawai/{id}/presensi/{bulan}/{tahun}/{id_presensi}/edit', [PegawaiController::class, 'editPresensi']);
        Route::post('pegawai/{id}/presensi/{bulan}/{tahun}/{id_presensi}/edit', [PegawaiController::class, 'updatePresensi']);
        Route::get('pegawai/{id}/presensi/tampilkan', [PegawaiController::class, 'tampilkanPresensi']);
        Route::resource('pegawai', PegawaiController::class);
        Route::get('qrcode/generate', [QrcodeController::class, 'generateQrcode']);
        Route::get('qrcode/tampil/{id}', [QrcodeController::class, 'tampilQr']);
        Route::resource('qrcode', QrcodeController::class);
        Route::get('cuti/upload/{id}', [CutiController::class, 'upload']);
        Route::post('cuti/upload/{id}', [CutiController::class, 'storeUpload']);
        Route::resource('cuti', CutiController::class);

        Route::get('laporan', [LaporanAdminController::class, 'index']);
        Route::get('laporan/rekap/{bulan}/{tahun}', [LaporanAdminController::class, 'bulanTahun']);
        Route::get('laporan/rekap/{bulan}/{tahun}/pdf', [LaporanAdminController::class, 'bulanPdf']);
        Route::get('laporan/tanggal', [LaporanAdminController::class, 'tanggal']);
        Route::get('laporan/tanggal/excel', [LaporanAdminController::class, 'excel']);
        Route::get('laporan/rekap', [LaporanAdminController::class, 'bulan']);
        Route::get('generate/presensi', [AdminController::class, 'generate']);
        Route::get('tampilgenerate', [AdminController::class, 'tampilgenerate']);
        Route::post('ringkasan/create', [RingkasanController::class, 'tambahPegawai']);
        Route::get('ringkasan/{id}/delete', [RingkasanController::class, 'delete']);
        Route::get('ringkasan/{id}/hitung/{bulan}/{tahun}', [RingkasanController::class, 'hitung']);
        Route::get('laporan/rekap/{bulan}/{tahun}/hitungsemua', [RingkasanController::class, 'hitungSemua']);
        Route::get('laporan/rekap/{bulan}/{tahun}/masukkanpegawai', [RingkasanController::class, 'masukkanPegawai']);
    });
});


Route::group(['middleware' => ['auth', 'role:superadmin']], function () {
    Route::prefix('superadmin')->group(function () {
        Route::get('pegawai', [SuperadminController::class, 'pegawai']);
        Route::get('presensipegawai/{id}', [SuperadminController::class, 'deletePresensi']);
        Route::get('pegawai/{id}/history', [SuperadminController::class, 'history']);
        Route::get('pegawai/{id}/history/tampilkan', [SuperadminController::class, 'tampilkanHistory']);
        Route::get('pegawai/search', [SuperadminController::class, 'searchPegawai']);
        Route::get('laporan/tanggal', [LaporanAdminController::class, 'tanggalSuperadmin']);
        Route::resource('jam', JamController::class);
        Route::get('skpd/{skpd_id}/resetpass', [SkpdController::class, 'resetpass']);
        Route::get('skpd/{skpd_id}/buatakun', [SkpdController::class, 'buatakun']);
        Route::get('skpd/{skpd_id}/detail', [SkpdController::class, 'detail']);
        Route::get('skpd/{skpd_id}/pegawai', [SkpdController::class, 'pegawai']);
        Route::get('skpd/{skpd_id}/pegawai/search', [SkpdController::class, 'searchPegawai']);
        Route::get('skpd/{skpd_id}/laporan', [SkpdController::class, 'laporan']);
        Route::get('skpd/{skpd_id}/lokasi', [SkpdController::class, 'lokasi']);
        Route::get('skpd/{skpd_id}/pegawai/{id_pegawai}/resetpass', [SkpdController::class, 'resetPassPegawai']);

        Route::get('skpd/{skpd_id}/cuti', [SkpdController::class, 'cuti']);
        Route::resource('skpd', SkpdController::class);
        Route::resource('rentang', RentangController::class);
        Route::resource('libur', LiburNasionalController::class);
        Route::resource('jenis', JenisKeteranganController::class);
        Route::get('generatetanggal', [GenerateController::class, 'index']);
        Route::get('notnull', [GenerateController::class, 'notnull']);
        Route::get('tarikpegawai', [GenerateController::class, 'tarikpegawai']);
        Route::get('limaharikerja', [GenerateController::class, 'limaharikerja']);
        Route::get('hitungpresensi', [GenerateController::class, 'hitungpresensi']);
        Route::get('hitungtotaljam', [GenerateController::class, 'hitungtotaljam']);
        //Route::get('hitungterlambat', [GenerateController::class, 'hitungterlambat']);
        Route::post('hitungterlambat', [GenerateController::class, 'hitungterlambat']);
        Route::get('totalterlambat', [GenerateController::class, 'totalterlambat']);

        Route::get('ringkasanpegawai', [GenerateController::class, 'ringkasanpegawai']);
        Route::get('generatetanggal/{bulan}', [GenerateController::class, 'generate']);

        Route::get('hitungcuti', [GenerateController::class, 'hitungcuti']);
        Route::get('cuti', [SuperadminController::class, 'cuti']);
        Route::get('rekapitulasi', [SuperadminController::class, 'rekapitulasi']);
        Route::get('rekapitulasi/{bulan}/{tahun}', [SuperadminController::class, 'detailRekapitulasi']);
    });
});

Route::group(['middleware' => ['auth', 'role:puskesmas']], function () {
    Route::prefix('puskesmas')->group(function () {
        Route::get('pegawai', [PuskesmasController::class, 'pegawai']);
        Route::get('pegawai/search', [PuskesmasController::class, 'searchpegawai']);
        Route::get('pegawai/{id}/resetpass', [PegawaiController::class, 'resetpass']);
        Route::get('pegawai/{id}/presensi/{bulan}/{tahun}', [PuskesmasController::class, 'detailPresensi']);
        Route::get('pegawai/{id}/presensi/{bulan}/{tahun}/{id_presensi}/edit', [PuskesmasController::class, 'editPresensi']);
        Route::post('pegawai/{id}/presensi/{bulan}/{tahun}/{id_presensi}/edit', [PuskesmasController::class, 'updatePresensi']);
        Route::get('pegawai/{id}/presensi', [PuskesmasController::class, 'presensi']);
        Route::get('cuti', [PuskesmasController::class, 'cuti']);
        Route::get('pegawai/{id}/jenispresensi', [PuskesmasController::class, 'jenispresensi']);
        Route::post('pegawai/{id}/jenispresensi', [PuskesmasController::class, 'updatejenispresensi']);
        Route::get('cuti/{id}/delete', [PuskesmasController::class, 'deletecuti']);
        Route::get('cuti/create', [PuskesmasController::class, 'createcuti']);
        Route::post('cuti/create', [PuskesmasController::class, 'storecuti']);
        Route::get('gantipass', [PuskesmasController::class, 'gantipass']);
        Route::post('gantipass', [PuskesmasController::class, 'updatepass']);
        Route::get('laporan', [PuskesmasController::class, 'laporan']);
        Route::get('laporan/rekap/{bulan}/{tahun}', [PuskesmasController::class, 'bulanTahun']);
        Route::get('laporan/rekap/{bulan}/{tahun}/pdf', [PuskesmasController::class, 'bulanPdf']);
        Route::get('laporan/rekap/{bulan}/{tahun}/hitungsemua', [PuskesmasController::class, 'hitungSemua']);
        Route::get('laporan/rekap/{bulan}/{tahun}/masukkanpegawai', [PuskesmasController::class, 'masukkanPegawai']);
        Route::post('ringkasan/create', [PuskesmasController::class, 'tambahPegawai']);
        Route::get('ringkasan/{id}/delete', [PuskesmasController::class, 'deleteRingkasan']);
        Route::get('ringkasan/{id}/hitung/{bulan}/{tahun}', [PuskesmasController::class, 'hitung']);
    });
});

Route::group(['middleware' => ['auth', 'role:superadmin|admin|pegawai|puskesmas']], function () {
    Route::get('/home/superadmin', [HomeController::class, 'superadmin']);
    Route::get('/home/admin', [HomeController::class, 'admin']);
    Route::get('/home/pegawai', [HomeController::class, 'pegawai']);
    Route::get('/home/puskesmas', [HomeController::class, 'puskesmas']);
});
