<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Komando;
use App\Models\Pegawai;
use App\Models\Presensi;
use App\Models\LiburNasional;
use Illuminate\Console\Command;

class PerbaikanJamKerja extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //protected $signature = 'perbaikanjam {--jenispresensi=} {--bulan=} {--tahun=}';
    protected $signature = 'perbaikanjam {--bulan=} {--tahun=} {--skpd_id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perbaikan Jam Kerja';

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

        //$jenispresensi = $this->option('jenispresensi');
        $bulan = $this->option('bulan');
        $tahun = $this->option('tahun');
        $skpd_id = $this->option('skpd_id');

        $pegawai = Pegawai::where('skpd_id', $skpd_id)->get();

        foreach ($pegawai as $p) {
            $presensi = Presensi::where('nip', $p->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();
            //dd($presensi, 'asd');
            foreach ($presensi as $pre) {
                if ($pre->terlambat > 0) {
                    $pre->update([
                        'terlambat' => 0,
                        'jam_masuk' => $pre->tanggal . ' 07:23:09',
                    ]);
                } else {
                }
                if ($pre->lebih_awal > 0) {
                    $pre->update([
                        'jam_pulang' => $pre->tanggal . ' 17:23:09',
                        'lebih_awal' => 0,
                    ]);
                } else {
                }

                // if ($pre->jenis_keterangan_id != null) {
                //     return 'cuti';
                // } else {
                //     if (Carbon::parse($pre->tanggal)->isWeekend()) {
                //         return 'weekend';
                //     } else {
                //         if (LiburNasional::where('tanggal', $pre->tanggal)->first() != null) {
                //             return 'libur nasional';
                //         } else {

                //         }
                //     }
                // }
            }
        }

        $com['nama_command'] = 'Perbaikan Jam Disdik';
        $com['waktu_eksekusi'] = Carbon::now()->format('Y-m-d H:i:s');

        Komando::create($com);
    }
}
