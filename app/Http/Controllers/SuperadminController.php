<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cuti;
use App\Models\Skpd;
use App\Models\Kunci;
use App\Models\Pegawai;
use App\Models\Presensi;
use App\Models\Puskesmas;
use App\Models\Ringkasan;
use App\Exports\laporan2022;
use App\Exports\laporan2023;
use App\Exports\laporan2024;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class SuperadminController extends Controller
{
    public function laporan2022()
    {
        return Excel::download(new laporan2022, 'absensi2022.xlsx');
    }
    public function laporan2023()
    {
        return Excel::download(new laporan2023, 'absensi2023.xlsx');
    }
    public function laporan2024()
    {
        return Excel::download(new laporan2024, 'absensi2024.xlsx');
    }

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

    public function resetdevice($id)
    {
        $pegawai = Pegawai::find($id)->user->update([
            'android_id' => null,
            'device_info' => null,
        ]);
        toastr()->success('Reset Device');
        return back();
    }

    public function tampilkanHistory($id)
    {
        if (request()->button == '1') {
            $bulan = request()->bulan;
            $tahun = request()->tahun;
            $pegawai = Pegawai::find($id);
            $data = Presensi::where('nip', $pegawai->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->orderBy('tanggal', 'ASC')->get();
            request()->flash();
            return view('superadmin.pegawai.history', compact('pegawai', 'data'));
        } else {
            $bulan = request()->bulan;
            $tahun = request()->tahun;
            $pegawai = Pegawai::find($id);
            $data = Presensi::where('nip', $pegawai->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->orderBy('tanggal', 'ASC')->get();
            foreach ($data as $key => $item) {
                $double = Presensi::where('nip', $pegawai->nip)->where('tanggal', $item->tanggal)->get();
                if (count($double) == 2) {
                    $double->first()->delete();
                } else {
                }
            }
            return back();
        }
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

    public function validasibkd($bulan, $tahun, $skpd_id)
    {
        $check = Kunci::where('skpd_id', $skpd_id)->where('bulan', $bulan)->where('tahun', $tahun)->first();
        if ($check == null) {
            $n = new Kunci();
            $n->skpd_id = $skpd_id;
            $n->bulan = $bulan;
            $n->tahun = $tahun;
            $n->validasi_skpd = 1;
            $n->save();
            toastr()->success('berhasil di validasi');
            return back();
        } else {
            $check->update([
                'validasi_bkd' => 1,
            ]);
            toastr()->success('berhasil di validasi');
            return back();
        }

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

    public function lockSkpd($bulan, $tahun, $skpd_id)
    {
        $check = Kunci::where('skpd_id', $skpd_id)->where('bulan', $bulan)->where('tahun', $tahun)->first();
        if ($check == null) {
            $n = new Kunci;
            $n->skpd_id = $skpd_id;
            $n->bulan = $bulan;
            $n->tahun = $tahun;
            $n->lock = 1;
            $n->save();
            toastr()->success('Berhasil Di kunci');
        } else {
            $check->update([
                'lock' => 1,
            ]);
            toastr()->success('Berhasil Di kunci');
        }
        return back();
    }

    public function unlockSkpd($bulan, $tahun, $skpd_id)
    {
        $check = Kunci::where('skpd_id', $skpd_id)->where('bulan', $bulan)->where('tahun', $tahun)->first();
        $check->update([
            'lock' => null,
        ]);
        toastr()->success('Berhasil Di Buka');
        return back();
    }

    public function lockPuskesmas($bulan, $tahun, $puskesmas_id)
    {
        $check = Kunci::where('puskesmas_id', $puskesmas_id)->where('bulan', $bulan)->where('tahun', $tahun)->first();
        if ($check == null) {
            $n = new Kunci;
            $n->puskesmas_id = $puskesmas_id;
            $n->bulan = $bulan;
            $n->tahun = $tahun;
            $n->lock = 1;
            $n->save();
            toastr()->success('Berhasil Di kunci');
        } else {
            $check->update([
                'lock' => 1,
            ]);
            toastr()->success('Berhasil Di kunci');
        }
        return back();
    }

    public function unlockPuskesmas($bulan, $tahun, $puskesmas_id)
    {
        $check = Kunci::where('puskesmas_id', $puskesmas_id)->where('bulan', $bulan)->where('tahun', $tahun)->first();
        $check->update([
            'lock' => null,
        ]);
        toastr()->success('Berhasil Di Buka');
        return back();
    }

    public function puskesmas()
    {
        $data = Puskesmas::get();
    }
}
