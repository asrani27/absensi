<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanAdminController extends Controller
{
    public function index()
    {
        $bulan = Carbon::today()->format('m');
        $tahun = Carbon::today()->format('Y');
        return view('admin.laporan.index',compact('bulan','tahun'));
    }

    public function tanggal()
    {
        $tanggal = request()->tanggal;
        $skpd = Auth::user()->skpd;

        $data = Presensi::where('skpd_id', $skpd->id)->where('tanggal', $tanggal)->get();
        return view('admin.laporan.tanggal',compact('data','skpd','tanggal'));
    }
    
    public function bulan()
    {
        $bulan = request()->bulan;
        $tahun = request()->tahun;
        $skpd = Auth::user()->skpd;
        $pegawai = Presensi::where('skpd_id', $skpd->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->groupBy(function($item){
            $item->nip;
        });
        
        return view('admin.laporan.tanggal',compact('data','skpd','tanggal'));
    }
}
