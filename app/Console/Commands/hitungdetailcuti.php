<?php

namespace App\Console\Commands;

use App\Models\Cuti;
use App\Models\Pegawai;
use Carbon\CarbonPeriod;
use App\Models\DetailCuti;
use App\Models\LiburNasional;
use Illuminate\Console\Command;

class hitungdetailcuti extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hitungdetailcuti';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detail Cuti';

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
        $cuti = Cuti::get();
        foreach ($cuti as $c) {
            $period     = CarbonPeriod::create($c->tanggal_mulai, $c->tanggal_selesai);
            $pegawai    = Pegawai::where('nip', $c->nip)->first();
            foreach ($period as $date) {
                if ($pegawai->jenis_presensi == 1) {
                    if ($date->translatedFormat('l') == 'Sabtu') {
                    } elseif ($date->translatedFormat('l') == 'Minggu') {
                    } else {
                        if (LiburNasional::where('tanggal', $date->format('Y-m-d'))->first() == null) {
                            //simpan cuti tahun di presensi
                            $n = new DetailCuti;
                            $n->cuti_id             = $c->id;
                            $n->nip                 = $c->nip;
                            $n->skpd_id             = $pegawai->skpd_id;
                            $n->tanggal             = $date->format('Y-m-d');
                            $n->jenis_keterangan_id = $c->jenis_keterangan_id;
                            $n->save();
                        } else {
                        }
                    }
                } else {
                    if ($date->translatedFormat('l') == 'Minggu') {
                    } else {
                        if (LiburNasional::where('tanggal', $date->format('Y-m-d'))->first() == null) {
                            //simpan cuti tahun di presensi
                            $n = new DetailCuti;
                            $n->cuti_id             = $c->id;
                            $n->nip                 = $c->nip;
                            $n->skpd_id             = $pegawai->skpd_id;
                            $n->tanggal             = $date->format('Y-m-d');
                            $n->jenis_keterangan_id = $c->jenis_keterangan_id;
                            $n->save();
                        } else {
                        }
                    }
                }
            }
        }
    }
}
