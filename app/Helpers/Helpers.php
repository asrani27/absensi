<?php

use Carbon\Carbon;
use App\Models\Kunci;
use App\Models\Presensi;
use Carbon\CarbonPeriod;
use App\Models\Ringkasan;
use App\Models\BulanTahun;
use App\Models\LiburNasional;

function bulanTahun()
{
    return BulanTahun::orderBy('id', 'DESC')->get();
}

function convertBulan($bulan)
{
    if ($bulan == '01') {
        $hasil = 'Januari';
    } elseif ($bulan == '02') {
        $hasil = 'Februari';
    } elseif ($bulan == '03') {
        $hasil = 'Maret';
    } elseif ($bulan == '04') {
        $hasil = 'April';
    } elseif ($bulan == '05') {
        $hasil = 'Mei';
    } elseif ($bulan == '06') {
        $hasil = 'Juni';
    } elseif ($bulan == '07') {
        $hasil = 'Juli';
    } elseif ($bulan == '08') {
        $hasil = 'Agustus';
    } elseif ($bulan == '09') {
        $hasil = 'September';
    } elseif ($bulan == '10') {
        $hasil = 'Oktober';
    } elseif ($bulan == '11') {
        $hasil = 'November';
    } elseif ($bulan == '12') {
        $hasil = 'Desember';
    }
    return $hasil;
}
function kunciSkpd($skpd_id, $bulan, $tahun)
{
    $check = Kunci::where('skpd_id', $skpd_id)->where('bulan', $bulan)->where('tahun', $tahun)->first();
    if ($check == null) {
        $hasil = null;
    } else {
        $hasil = $check->lock;
    }
    return $hasil;
}

function kunciPuskesmas($puskesmas_id, $bulan, $tahun)
{
    $check = Kunci::where('puskesmas_id', $puskesmas_id)->where('bulan', $bulan)->where('tahun', $tahun)->first();
    if ($check == null) {
        $hasil = null;
    } else {
        $hasil = $check->lock;
    }
    return $hasil;
}
function telat($nip, $bulan, $tahun)
{
    return Presensi::where('nip', $nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();
}

function persenKehadiran($nip, $bulan, $tahun)
{
    return Ringkasan::where('nip', $nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
}

function jumlahHari($bulan, $tahun)
{
    $tanggalmerah = LiburNasional::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('deskripsi', 'cuti bersama')->get()->pluck('tanggal')->toArray();
    //dd($tanggalmerah);
    ///$cutibersama = LiburNasional::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('deskripsi', 'cuti bersama')->get()->count();
    $weekends = [];
    $start = Carbon::createFromFormat('m/Y', $bulan . '/' . $tahun)->startOfMonth();
    $end = Carbon::createFromFormat('m/Y', $bulan . '/' . $tahun)->endOfMonth();
    $period = CarbonPeriod::create($start, $end);
    $dates = [];
    foreach ($period as $date) {
        if ($date->isWeekend()) {
            array_push($weekends, $date->format('Y-m-d'));
        }
        $dates[] = $date->format('Y-m-d');
    }
    $array_merge = array_merge($weekends, $tanggalmerah);
    $jumlah_hari_kerja = collect($dates)->diff($array_merge);

    $jumlah_jam = [];
    foreach ($jumlah_hari_kerja as $item) {
        $jumlah_jam[] = Carbon::parse($item)->format('l') == 'Friday' ? 210 : 510;
    }

    $data['jumlah_hari'] = count($jumlah_hari_kerja);
    $data['jumlah_jam'] = array_sum($jumlah_jam);
    $data['off'] = count($dates) -  count($jumlah_hari_kerja);
    return $data;
}

function jumlahHari6($bulan, $tahun)
{
    $tanggalmerah = LiburNasional::whereMonth('tanggal', $bulan)->where('deskripsi', 'cuti bersama')->whereYear('tanggal', $tahun)->get()->pluck('tanggal')->toArray();
    $weekends = [];
    $start = Carbon::createFromFormat('m/Y', $bulan . '/' . $tahun)->startOfMonth();
    $end = Carbon::createFromFormat('m/Y', $bulan . '/' . $tahun)->endOfMonth();
    $period = CarbonPeriod::create($start, $end);
    $dates = [];
    foreach ($period as $date) {
        if ($date->translatedFormat('l') == 'Minggu') {
            array_push($weekends, $date->format('Y-m-d'));
        }
        $dates[] = $date->format('Y-m-d');
    }
    $array_merge = array_merge($weekends, $tanggalmerah);
    $jumlah_hari_kerja = collect($dates)->diff($array_merge);

    $jumlah_jam = [];
    foreach ($jumlah_hari_kerja as $item) {
        if (Carbon::parse($item)->translatedFormat('l') == 'Jumat') {
            $jumlah_jam[] = 210;
        } elseif (Carbon::parse($item)->translatedFormat('l') == 'Sabtu') {
            $jumlah_jam[] = 420;
        } else {
            $jumlah_jam[] = 360;
        }
    }

    $data['jumlah_hari'] = count($jumlah_hari_kerja);
    $data['jumlah_jam'] = array_sum($jumlah_jam);
    $data['off'] = count($dates) -  count($jumlah_hari_kerja);
    return $data;
}

function distance($lat1, $lon1, $lat2, $lon2, $unit)
{
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
        return ($miles * 1.609344);
    } else if ($unit == "N") {
        return ($miles * 0.8684);
    } else {
        return $miles;
    }
}
