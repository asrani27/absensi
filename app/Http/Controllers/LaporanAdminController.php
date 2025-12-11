<?php

namespace App\Http\Controllers;

use Excel;
use Carbon\Carbon;
use App\Models\Skpd;
use App\Models\Kunci;
use GuzzleHttp\Client;
use App\Models\Pegawai;
use App\Models\Presensi;
use App\Models\Ringkasan;
use App\Models\DoubleData;
use App\Models\PresensiApel;
use Illuminate\Http\Request;
use App\Exports\PresensiExport;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LaporanAdminController extends Controller
{
    public function validasi($bulan, $tahun)
    {
        $check = Kunci::where('skpd_id', Auth::user()->skpd->id)->where('bulan', $bulan)->where('tahun', $tahun)->first();
        if ($check == null) {
            $n = new Kunci();
            $n->skpd_id = Auth::user()->skpd->id;
            $n->bulan = $bulan;
            $n->tahun = $tahun;
            $n->validasi_skpd = 1;
            $n->save();
            toastr()->success('berhasil di validasi');
            return back();
        } else {
            $check->update([
                'validasi_skpd' => 1,
            ]);
            toastr()->success('berhasil di validasi');
            return back();
        }
    }
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

        // Get pegawai data for the current SKPD
        $datapegawai = Pegawai::where('skpd_id', $skpd->id)
            ->where('puskesmas_id', null)
            ->where('jabatan', '!=', null)
            ->orderBy('urutan', 'DESC')
            ->get();

        // Get all NIPs from pegawai for efficient querying
        $nipList = $datapegawai->pluck('nip');

        // Get presensi data only for relevant pegawai
        $presensiData = Presensi::where('tanggal', $tanggal)
            ->whereIn('nip', $nipList)
            ->get()
            ->groupBy('nip');

        // Get presensi apel data only for relevant pegawai
        $presensiApelData = PresensiApel::where('tanggal', $tanggal)
            ->whereIn('nip', $nipList)
            ->pluck('jam', 'nip');

        // Prepare data for batch operations
        $defaultPresensiToInsert = [];
        $doubleDataToInsert = [];

        // Process data and identify records to create
        foreach ($datapegawai as $pegawai) {
            $presensiRecords = $presensiData->get($pegawai->nip, collect());

            if ($presensiRecords->count() == 0) {
                // Prepare default presensi for batch insert
                $defaultPresensiToInsert[] = [
                    'nip' => $pegawai->nip,
                    'nama' => $pegawai->nama,
                    'skpd_id' => $pegawai->skpd_id,
                    'tanggal' => $tanggal,
                    'jam_masuk' => $tanggal . ' 00:00:00',
                    'jam_pulang' => $tanggal . ' 00:00:00',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            } elseif ($presensiRecords->count() > 1) {
                // Prepare double data for batch insert
                $doubleDataToInsert[] = [
                    'nip' => $pegawai->nip,
                    'tanggal' => $tanggal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Batch insert operations
        if (!empty($defaultPresensiToInsert)) {
            Presensi::insert($defaultPresensiToInsert);
        }

        if (!empty($doubleDataToInsert)) {
            DoubleData::insert($doubleDataToInsert);
        }

        // Refresh presensi data after potential inserts
        if (!empty($defaultPresensiToInsert)) {
            $presensiData = Presensi::where('tanggal', $tanggal)
                ->whereIn('nip', $nipList)
                ->get()
                ->groupBy('nip');
        }

        // Map data with optimized lookups
        $data = $datapegawai->map(function ($pegawai) use ($presensiData, $presensiApelData) {
            $pegawai->presensi_apel = $presensiApelData->get($pegawai->nip, '-');

            $presensiRecords = $presensiData->get($pegawai->nip, collect());
            $pegawai->presensi = $presensiRecords->first();

            return $pegawai;
        });

        $pimpinan = $datapegawai->first();
        return view('admin.laporan.tanggal', compact('data', 'skpd', 'tanggal', 'pimpinan'));
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
        $data = Ringkasan::where('bulan', $bulan)->where('tahun', $tahun)->where('puskesmas_id', null)->where('sekolah_id', null)->where('skpd_id', Auth::user()->skpd->id)->get()
            ->map(function ($item) {
                $check = Pegawai::where('nip', $item->nip)->first();
                $item->urut = $check == null ? 0 : $check->urutan;
                return $item;
            })->sortByDesc('urut');
        //dd($data);
        return view('admin.laporan.bulantahun', compact('bulan', 'tahun', 'data'));
    }


    public function bulanTahunSekolah($bulan, $tahun)
    {
        $data = Ringkasan::where('bulan', $bulan)->where('tahun', $tahun)->where('puskesmas_id', null)->where('sekolah_id', '!=', null)->where('skpd_id', Auth::user()->skpd->id)->get()
            ->map(function ($item) {
                $check = Pegawai::where('nip', $item->nip)->first();
                $item->urut = $check == null ? 0 : $check->urutan;
                return $item;
            })->sortByDesc('urut');
        return view('admin.laporan.bulantahunsekolah', compact('bulan', 'tahun', 'data'));
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
