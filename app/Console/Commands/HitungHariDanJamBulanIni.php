<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Komando;
use App\Models\Pegawai;
use Carbon\CarbonPeriod;
use App\Models\Ringkasan;
use App\Models\LiburNasional;
use Illuminate\Console\Command;

class HitungHariDanJamBulanIni extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hitungharidanjam';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Total Hari Dan Jam Kerja Bulan Ini';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
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
        $pegawai = Pegawai::where('jenis_presensi', '1')->get();
        foreach ($pegawai as $item) {
            $check = Ringkasan::where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            if ($check == null) {
                $n = new Ringkasan;
                $n->nip = $item->nip;
                $n->nama = $item->nama;
                $n->jabatan = $item->jabatan;
                $n->jumlah_hari = count($jumlah_hari_kerja);
                $n->jumlah_jam = array_sum($jumlah_jam);
                $n->save();
            } else {
                $item->update([
                    'jumlah_hari' => count($jumlah_hari_kerja),
                    'jumlah_jam' => array_sum($jumlah_jam),
                ]);
            }
        }
        // foreach ($ringkasan as $item) {
        //     $check = Pegawai::where('nip', $item->nip)->first();
        //     //dd($check, $item);
        //     if ($check->jenis_presensi == 1) {
        //         $item->update([
        //             'jumlah_hari' => count($jumlah_hari_kerja),
        //             'jumlah_jam' => array_sum($jumlah_jam),
        //         ]);
        //     } else {
        //     }
        // }

        $com['nama_command'] = 'hitung hari dan jam bulan ini';
        $com['waktu_eksekusi'] = Carbon::now()->format('Y-m-d H:i:s');

        Komando::create($com);
    }
}
