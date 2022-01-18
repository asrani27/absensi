<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Jam;
use GuzzleHttp\Client;
use App\Models\Pegawai;
use App\Models\Presensi;
use Carbon\CarbonPeriod;
use App\Models\Ringkasan;
use App\Jobs\NotNullProcess;
use Illuminate\Http\Request;
use App\Jobs\HitungTerlambat;
use App\Models\LiburNasional;
use App\Jobs\SyncPegawaiAdmin;

class GenerateController extends Controller
{
    public function generate($bulan)
    {
        $year   = Carbon::today()->format('Y');
        $month  = $year . '-' . $bulan;

        $start  = Carbon::parse($month)->startOfMonth();
        $end    = Carbon::parse($month)->endOfMonth();
        $period = CarbonPeriod::create($start, $end);

        $pegawai = Pegawai::get();

        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        $pegawai->map(function ($item) use ($dates) {
            foreach ($dates as $d) {
                $check = Presensi::where('nip', $item->nip)->where('tanggal', $d)->first();
                if ($check == null) {
                    $p = new Presensi;
                    $p->nip = $item->nip;
                    $p->tanggal = $d;
                    $p->save();
                } else {
                }
            }
            return $item;
        });
        toastr()->success('Berhasil Di Generate');
        return back();
    }

    public function index()
    {
        return view('superadmin.generate.tanggal');
    }

    public function notnull()
    {
        $data = Presensi::where('jam_masuk', null)->orWhere('jam_pulang', null)->take(1000)->get();

        foreach ($data as $item) {
            NotNullProcess::dispatch($item);
        }

        toastr()->success('Berhasil Di Generate');
        return back();
    }

    public function tarikpegawai()
    {
        $client = new Client(['base_uri' => 'https://tpp.banjarmasinkota.go.id/api/']);
        $response = $client->request('get', 'pegawai', ['verify' => false]);
        $data =  json_decode($response->getBody())->data;

        foreach ($data as $item) {
            SyncPegawaiAdmin::dispatch($item);
        }

        toastr()->success('Berhasil Di tarik');
        return back();
    }

    public function limaharikerja()
    {
        $data = Pegawai::get();
        foreach ($data as $item) {
            $item->update(['jenis_presensi' => 1]);
        }
        toastr()->success('Berhasil Di Generate, semua pegawai masuk dalam presensi 5 hari kerja');
        return back();
    }

    public function hitungpresensi()
    {
        //hitung jumlah hari bulan ini di potong sabtu dan minggu dan hari libur
        $bulan = Carbon::now()->format('m');
        $tahun = Carbon::now()->format('Y');
        $tanggalmerah = LiburNasional::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->pluck('tanggal')->toArray();
        $weekends = [];
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();
        $period = CarbonPeriod::create($start, $end);
        $dates = [];
        foreach ($period as $date) {
            if ($date->isWeekend()) {
                array_push($weekends, $date->format('Y-m-d'));
            }
            $dates[] = $date->format('Y-m-d');
        }
        $array_merge = array_merge($weekends, $tanggalmerah);
        $jumlah_hari_kerja = collect($dates)->diff($array_merge);

        $jumlah_jam = [];
        foreach ($jumlah_hari_kerja as $item) {
            $jumlah_jam[] = Carbon::parse($item)->format('l') == 'Friday' ? 210 : 510;
        }
        //dd(array_sum($jumlah_jam));
        //Update Jumlah Hari Kerja Di Tabel Ringkasan        
        $ringkasan = Ringkasan::where('bulan', $bulan)->where('tahun', $tahun)->get();
        //dd($ringkasan);
        foreach ($ringkasan as $item) {
            $check = Pegawai::where('nip', $item->nip)->first();
            //dd($check, $item);
            if ($check->jenis_presensi == 1) {
                $item->update([
                    'jumlah_hari' => count($jumlah_hari_kerja),
                    'jumlah_jam' => array_sum($jumlah_jam),
                ]);
            } else {
            }
        }
        //$ringkasan = Ringkasan::where()
        toastr()->success('Berhasil Di Generate');
        return back();
        // dd($dates, $array_merge, $jumlah_hari_kerja);
        // dd($start, $end, $period, $dates, $weekends, $tanggalmerah, $array_merge);
        //        $totaldate = Carbon::
    }

    public function hitungterlambat(Request $req)
    {
        if (Carbon::parse($req->tanggal)->isWeekend() == true) {
            $presensi = Presensi::where('tanggal', $today)->get();
            foreach ($presensi as $item) {
                $item->update([
                    'terlambat' => 0,
                    'lebih_awal' => 0,
                ]);
            }
            toastr()->success('ini adalah hari weekend');
        } else {
            if (LiburNasional::where('tanggal', $req->tanggal)->first() != null) {
                $presensi = Presensi::where('tanggal', $today)->get();
                foreach ($presensi as $item) {
                    $item->update([
                        'terlambat' => 0,
                        'lebih_awal' => 0,
                    ]);
                }
                toastr()->success('ini adalah hari libur nasional');
            } else {
                $today = Carbon::today()->format('Y-m-d');
                $hari = Carbon::today()->translatedFormat('l');
                $jam = Jam::where('hari', $hari)->first();
                //dd($hari, $jam);
                $presensi = Presensi::where('tanggal', $today)->get();
                foreach ($presensi as $item) {
                    HitungTerlambat::dispatch($item, $jam);
                }

                toastr()->success('Selesai Di Hitung');
            }
        }

        $req->flash();
        return back();
    }

    public function ringkasanpegawai()
    {
        $bulan = Carbon::now()->format('m');
        $tahun = Carbon::now()->format('Y');
        $pegawai = Pegawai::get();
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
                $r->save();
            } else {
            }
        }
        toastr()->success('Berhasil Di Generate');
        return back();
    }
}
