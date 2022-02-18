<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Pegawai;
use Carbon\CarbonPeriod;
use App\Models\Ringkasan;
use App\Models\LiburNasional;
use Illuminate\Console\Command;

class HitungHariDanJam6 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hitungharidanjam6';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $bulan = Carbon::now()->subMonth(1)->format('m');
        $tahun = Carbon::now()->subMonth(1)->format('Y');
        $tanggalmerah = LiburNasional::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->pluck('tanggal')->toArray();
        $weekends = [];
        $start = Carbon::now()->subMonth(1)->startOfMonth();
        $end = Carbon::now()->subMonth(1)->endOfMonth();
        $period = CarbonPeriod::create($start, $end);
        $dates = [];
        foreach ($period as $date) {
            if ($date->translatedFormat('l') == 'Minggu') {
                array_push($weekends, $date->format('Y-m-d'));
            }
            $dates[] = $date->format('Y-m-d');
        }
        $array_merge = array_merge($weekends, $tanggalmerah);
        $jumlah_hari_kerja = collect($dates)->diff($array_merge);

        $jumlah_jam = [];
        foreach ($jumlah_hari_kerja as $item) {
            if (Carbon::parse($item)->translatedFormat('l') == 'Jumat') {
                $jumlah_jam[] = 210;
            } elseif (Carbon::parse($item)->translatedFormat('l') == 'Sabtu') {
                $jumlah_jam[] = 420;
            } else {
                $jumlah_jam[] = 360;
            }
        }

        $pegawai = Pegawai::where('jenis_presensi', '2')->get();
        foreach ($pegawai as $item) {
            $check = Ringkasan::where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            if ($check == null) {
                $n = new Ringkasan;
                $n->nip = $item->nip;
                $n->nama = $item->nama;
                $n->jabatan = $item->jabatan;
                $n->puskesmas_id = $item->puskesmas_id;
                $n->jumlah_hari = count($jumlah_hari_kerja);
                $n->jumlah_jam = array_sum($jumlah_jam);
                $n->save();
            } else {
                $item->update([
                    'jumlah_hari' => count($jumlah_hari_kerja),
                    'jumlah_jam' => array_sum($jumlah_jam),
                    'puskesmas_id' => $item->puskesmas_id,
                ]);
            }
        }
    }
}
