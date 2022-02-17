<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\Pegawai;
use App\Models\Presensi;
use Illuminate\Http\Request;

class SuperadminController extends Controller
{
    public function pegawai()
    {
        $data = Pegawai::orderBy('urutan', 'DESC')->paginate(10);
        return view('superadmin.pegawai.index', compact('data'));
    }

    public function searchPegawai()
    {
        $search = request()->search;
        $data  = Pegawai::where('nama', 'like', '%' . $search . '%')->orWhere('nip', 'like', '%' . $search . '%')->paginate(10)->withQueryString();
        request()->flash();
        return view('superadmin.pegawai.index', compact('data'));
    }

    public function history($id)
    {
        $pegawai = Pegawai::find($id);
        $data = null;
        return view('superadmin.pegawai.history', compact('pegawai', 'data'));
    }

    public function tampilkanHistory($id)
    {
        $bulan = request()->bulan;
        $tahun = request()->tahun;
        $pegawai = Pegawai::find($id);
        $data = Presensi::where('nip', $pegawai->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->orderBy('tanggal', 'ASC')->get();
        request()->flash();
        return view('superadmin.pegawai.history', compact('pegawai', 'data'));
    }

    public function cuti()
    {
        $data = Cuti::orderBy('id', 'DESC')->paginate(15);
        return view('superadmin.cuti.index', compact('data'));
    }
}
