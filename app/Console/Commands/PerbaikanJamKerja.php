<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Komando;
use App\Models\Pegawai;
use App\Models\Presensi;
use Illuminate\Console\Command;

class PerbaikanJamKerja extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'perbaikanjam {--jenispresensi=} {--bulan=} {--tahun=}';

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
        $jenispresensi = $this->option('jenispresensi');
        $bulan = $this->option('bulan');
        $tahun = $this->option('tahun');

        $pegawai = Pegawai::where('jenis_presensi', $jenispresensi)->get();
        foreach ($pegawai as $p) {
            $presensi = Presensi::where('nip', $p->nip)->get();
            foreach ($presensi as $pre) {
                $pre->update([
                    'terlambat' => 0,
                    'lebih_awal' => 0,
                ]);
            }
        }

        $com['nama_command'] = 'Perbaikan Jam, jenis Presensi ' . $jenispresensi;
        $com['waktu_eksekusi'] = Carbon::now()->format('Y-m-d H:i:s');

        Komando::create($com);
    }
}
