<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PresensiController extends Controller
{
    public function masuk()
    {
        return view('pegawai.presensi.masuk');
    }
    
    public function pulang()
    {
        return view('pegawai.presensi.pulang');
    }
}
