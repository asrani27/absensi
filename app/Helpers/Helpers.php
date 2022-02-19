<?php

use Carbon\Carbon;
use App\Models\Presensi;
use Carbon\CarbonPeriod;
use App\Models\Ringkasan;
use App\Models\BulanTahun;
use App\Models\LiburNasional;

function bulanTahun()
{
    return BulanTahun::orderBy('id', 'DESC')->get();
}

function telat($nip, $bulan, $tahun)
{
    return Presensi::where('nip', $nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();
}
function jumlahHari($bulan, $tahun)
{
    $tanggalmerah = LiburNasional::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->pluck('tanggal')->toArray();
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
    return $data;
}

function jumlahHari6($bulan, $tahun)
{
    $tanggalmerah = LiburNasional::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->pluck('tanggal')->toArray();
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
    return $data;
}
