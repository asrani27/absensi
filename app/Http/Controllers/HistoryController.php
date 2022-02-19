<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        $data = [];
        return view('pegawai.history.index', compact('data'));
    }

    public function search()
    {
        $bulan = request()->get('bulan');
        $tahun = request()->get('tahun');

        $data = Presensi::where('nip', Auth::user()->username)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->orderBy('tanggal', 'ASC')->get();
        request()->flash();
        return view('pegawai.history.index', compact('data', 'bulan', 'tahun'));
    }
}
