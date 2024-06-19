<?php

namespace App\Http\Controllers;

use App\Models\Loss;
use App\Models\Pegawai;
use App\Models\Presensi;


use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LossController extends Controller
{
    public function index()
    {
        $data = Loss::get()->map(function ($item) {
            $item->nama = Pegawai::where('nip', $item->nip)->first()->nama;
            $item->jabatan = Pegawai::where('nip', $item->nip)->first()->jabatan;
            $item->skpd = Pegawai::where('nip', $item->nip)->first()->skpd->nama;
            return $item;
        });

        return view('list', compact('data'));
    }

    public function hitung()
    {
        $data = Pegawai::orderBy('nama', 'asc')->get()->map(function ($item) {
            $item->terlambat = Presensi::where('nip', $item->nip)->whereYear('tanggal', '2023')->sum('terlambat');
            $item->lebih_awal = Presensi::where('nip', $item->nip)->whereYear('tanggal', '2023')->sum('lebih_awal');
            return $item;
        });
        return view('hitung', compact('data'));
    }
}
