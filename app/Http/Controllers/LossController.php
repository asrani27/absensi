<?php

namespace App\Http\Controllers;

use App\Models\Loss;
use App\Models\Pegawai;
use Illuminate\Http\Request;

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
}
