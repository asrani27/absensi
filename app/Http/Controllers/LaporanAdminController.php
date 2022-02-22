<?php

namespace App\Http\Controllers;

use Excel;
use Carbon\Carbon;
use App\Models\Skpd;
use GuzzleHttp\Client;
use App\Models\Pegawai;
use App\Models\Presensi;
use App\Models\Ringkasan;
use App\Models\DoubleData;
use Illuminate\Http\Request;
use App\Exports\PresensiExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;

class LaporanAdminController extends Controller
{
    public function index()
    {
        $bulan = Carbon::today()->format('m');
        $tahun = Carbon::today()->format('Y');
        $data = [];
        //$data = Ringkasan::where('bulan', $bulan)->where('tahun', $tahun)->where('skpd_id', Auth::user()->skpd->id)->get();
        //dd($data);
        return view('admin.laporan.index', compact('bulan', 'tahun', 'data'));
    }

    public function tanggalSuperadmin()
    {
        $skpd_id = request()->get('skpd_id');
        $tanggal = request()->get('tanggal');
        $skpd = Skpd::find($skpd_id);

        $data = Presensi::where('skpd_id', $skpd_id)->where('tanggal', $tanggal)->get();
        return view('superadmin.skpd.laporan.tanggal', compact('data', 'skpd', 'tanggal'));
    }

    public function tanggal()
    {
        $jenis = request()->jenis;
        $tanggal = request()->tanggal;
        $skpd = Auth::user()->skpd;
        if ($jenis == 'excel') {
            return Excel::download(new PresensiExport($tanggal, $skpd), 'presensi.xlsx');
        } else {

            $presensi = Presensi::where('skpd_id', $skpd->id)->where('tanggal', $tanggal)->get();

            $datapegawai = Pegawai::where('skpd_id', $skpd->id)->where('puskesmas_id', null)->where('jabatan', '!=', null)->orderBy('urutan', 'DESC')->get();

            //mapping data
            $data = $datapegawai->map(function ($item) use ($presensi, $tanggal) {
                $check = $presensi->where('nip', $item->nip);
                if (count($check) == 1) {
                    $item->presensi = $check->first();
                } elseif (count($check) == 0) {
                    //Buat Presensi Default
                    $p = new Presensi;
                    $p->nip = $item->nip;
                    $p->nama = $item->nama;
                    $p->skpd_id = $item->skpd_id;
                    $p->tanggal = $tanggal;
                    $p->jam_masuk = '00:00:00';
                    $p->jam_pulang = '00:00:00';
                    $p->save();
                } else {
                    //Log Data Double 
                    $d = new DoubleData;
                    $d->nip = $item->nip;
                    $d->tanggal = $tanggal;
                    $d->save();
                }
                return $item;
            });

            $pimpinan = $datapegawai->first();
            return view('admin.laporan.tanggal', compact('data', 'skpd', 'tanggal', 'pimpinan'));
        }
    }

    public function bulan()
    {
        $button = request()->button;
        $skpd = Auth::user()->skpd;
        $bulan   = request()->bulan;
        $tahun   = request()->tahun;
        if ($button == '1') {
            $pegawai = Presensi::where('skpd_id', $skpd->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->groupBy(function ($item) {
                $item->nip;
            });

            request()->flash();
            toastr()->error('Dalam Pengembangan');
            return back();
        } else {
            $pegawai = Pegawai::where('skpd_id', $skpd->id)->where('puskesmas_id', null)->where('is_aktif', 1)->orderBy('urutan', 'DESC')->get();
            //dd($pegawai);
            foreach ($pegawai as $item) {
                $check = Ringkasan::where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
                if ($check == null) {
                    $r = new Ringkasan;
                    $r->nip     = $item->nip;
                    $r->nama    = $item->nama;
                    $r->jabatan = $item->jabatan;
                    $r->skpd_id = $item->skpd_id;
                    $r->bulan   = $bulan;
                    $r->tahun   = $tahun;
                    $r->puskesmas_id   = $item->puskesmas_id;
                    $r->save();
                } else {
                    $check->update([
                        'puskesmas_id' => $item->puskesmas_id,
                    ]);
                }
            }

            $data = Ringkasan::where('bulan', $bulan)->where('tahun', $tahun)->where('puskesmas_id', null)->where('skpd_id', Auth::user()->skpd->id)->where('jabatan', '!=', null)->get()
                ->map(function ($item) {
                    $item->urut = Pegawai::where('nip', $item->nip)->first()->urutan;
                    return $item;
                })->sortByDesc('urut');

            request()->flash();

            return view('admin.laporan.index', compact('bulan', 'tahun', 'data'));
        }
    }

    public function bulanTahun($bulan, $tahun)
    {
        $data = Ringkasan::where('bulan', $bulan)->where('tahun', $tahun)->where('puskesmas_id', null)->where('skpd_id', Auth::user()->skpd->id)->get()
            ->map(function ($item) {
                $check = Pegawai::where('nip', $item->nip)->first();
                $item->urut = $check == null ? 0 : $check->urutan;
                return $item;
            })->sortByDesc('urut');

        return view('admin.laporan.bulantahun', compact('bulan', 'tahun', 'data'));
    }

    public function bulanPdf($bulan, $tahun)
    {
        $data = Ringkasan::where('bulan', $bulan)->where('tahun', $tahun)->where('puskesmas_id', null)->where('skpd_id', Auth::user()->skpd->id)->get()
            ->map(function ($item) {
                $check = Pegawai::where('nip', $item->nip)->first();
                $item->urut = $check == null ? 0 : $check->urutan;
                return $item;
            })->sortByDesc('urut');
        $skpd = Auth::user()->skpd;
        $mulai = Carbon::createFromFormat('m/Y', $bulan . '/' . $tahun)->firstOfMonth()->format('d-m-Y');
        $sampai = Carbon::createFromFormat('m/Y', $bulan . '/' . $tahun)->endOfMonth()->format('d-m-Y');

        $pdf = PDF::loadView('admin.laporan.bulanpdf', compact('data', 'skpd', 'mulai', 'sampai'))->setPaper('legal', 'landscape');
        return $pdf->stream();
    }
}
