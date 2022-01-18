<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Jam;
use App\Models\Komando;
use App\Models\Presensi;
use App\Jobs\HitungTerlambat;
use App\Models\LiburNasional;
use Illuminate\Console\Command;

class HitungTerlambatHariIni extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hitungterlambathariini';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hitung Terlambat dan Pulang Lebih Awal Hari Ini';

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
        $tanggal = Carbon::now()->format('Y-m-d');
        if (Carbon::parse($tanggal)->isWeekend() == true) {
            $presensi = Presensi::where('tanggal', $tanggal)->get();
            foreach ($presensi as $item) {
                $item->update([
                    'terlambat' => 0,
                    'lebih_awal' => 0,
                ]);
            }
            toastr()->success('ini adalah hari weekend');
        } else {
            if (LiburNasional::where('tanggal', $tanggal)->first() != null) {
                $presensi = Presensi::where('tanggal', $tanggal)->get();
                foreach ($presensi as $item) {
                    $item->update([
                        'terlambat' => 0,
                        'lebih_awal' => 0,
                    ]);
                }
                toastr()->success('ini adalah hari libur nasional');
            } else {
                $today = $tanggal;
                //Carbon::today()->format('Y-m-d');
                $hari = Carbon::parse($tanggal)->translatedFormat('l');
                //Carbon::today()->translatedFormat('l');
                $jam = Jam::where('hari', $hari)->first();
                //dd($hari, $jam);
                $presensi = Presensi::where('tanggal', $today)->get();
                foreach ($presensi as $item) {
                    HitungTerlambat::dispatch($item, $jam);
                }

                $com['nama_command'] = 'hitung terlambat ini';
                $com['waktu_eksekusi'] = Carbon::now()->format('Y-m-d H:i:s');

                Komando::create($com);
            }
        }
    }
}
