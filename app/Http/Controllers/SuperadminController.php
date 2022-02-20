<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cuti;
use App\Models\Skpd;
use App\Models\Pegawai;
use App\Models\Presensi;
use App\Models\Puskesmas;
use App\Models\Ringkasan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;

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

    public function rekapitulasi()
    {
        return view('superadmin.rekapitulasi.index');
    }

    public function detailRekapitulasi($bulan, $tahun)
    {
        $data = Ringkasan::where('bulan', $bulan)->where('tahun', $tahun)->orderBy('id', 'DESC')->get();
        return view('superadmin.rekapitulasi.detail', compact('data'));
    }

    public function deletePresensi($id)
    {
        Presensi::find($id)->delete();
        return back();
    }

    public function skpdRekapitulasi($bulan, $tahun)
    {
        $skpd = Skpd::get();
        $puskesmas = Puskesmas::get();
        return view('superadmin.detailskpd', compact('skpd', 'bulan', 'tahun', 'puskesmas'));
    }

    public function skpdPdf($bulan, $tahun, $id)
    {
        $data = Ringkasan::where('bulan', $bulan)->where('tahun', $tahun)->where('skpd_id', $id)->where('puskesmas_id', null)->where('jabatan', '!=', null)->get()
            ->map(function ($item) {
                $item->urut = Pegawai::where('nip', $item->nip)->first()->urutan;
                return $item;
            })->sortByDesc('urut');
        $skpd = Skpd::find($id);
        $mulai = Carbon::createFromFormat('m/Y', $bulan . '/' . $tahun)->firstOfMonth()->format('d-m-Y');
        $sampai = Carbon::createFromFormat('m/Y', $bulan . '/' . $tahun)->endOfMonth()->format('d-m-Y');

        $pdf = PDF::loadView('superadmin.skpdpdf', compact('data', 'skpd', 'mulai', 'sampai'))->setPaper('legal', 'landscape');
        return $pdf->stream();
    }

    public function puskesmasPdf($bulan, $tahun, $id)
    {
        $data = Ringkasan::where('bulan', $bulan)->where('tahun', $tahun)->where('puskesmas_id', $id)->where('jabatan', '!=', null)->get()
            ->map(function ($item) {
                $item->urut = Pegawai::where('nip', $item->nip)->first()->urutan;
                return $item;
            })->sortByDesc('urut');
        $skpd = Puskesmas::find($id);
        $mulai = Carbon::createFromFormat('m/Y', $bulan . '/' . $tahun)->firstOfMonth()->format('d-m-Y');
        $sampai = Carbon::createFromFormat('m/Y', $bulan . '/' . $tahun)->endOfMonth()->format('d-m-Y');

        $pdf = PDF::loadView('superadmin.puskesmaspdf', compact('data', 'skpd', 'mulai', 'sampai'))->setPaper('legal', 'landscape');
        return $pdf->stream();
    }
}
