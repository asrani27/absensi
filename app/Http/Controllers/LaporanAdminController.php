<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanAdminController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function tanggal()
    {
        $tanggal = request()->tanggal;
        $skpd = Auth::user()->skpd;

        $data = Presensi::where('skpd_id', $skpd->id)->where('tanggal', $tanggal)->get();
        return view('admin.laporan.tanggal',compact('data','skpd','tanggal'));
    }
}
