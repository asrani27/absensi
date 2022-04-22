<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Komando;
use App\Models\Pegawai;
use App\Models\Presensi;
use Illuminate\Console\Command;

class generateAbsensi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'absensi {--tanggal=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Absensi';

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
        if ($this->option('tanggal') != null) {
            $tanggal = $this->option('tanggal');
        } else {
            $tanggal = Carbon::now()->format('Y-m-d');
        }

        $pegawai = Pegawai::where('is_aktif', 1)->get();

        foreach ($pegawai as $item) {
            $p = Presensi::where('nip', $item->nip)->where('tanggal', $tanggal)->first();
            if ($p == null) {
                $attr['nip'] = $item->nip;
                $attr['nama'] = $item->nama;
                $attr['tanggal'] = $tanggal;
                $attr['jam_masuk'] = null;
                $attr['jam_pulang'] = null;
                $attr['skpd_id'] = $item->skpd_id;
                $attr['jenis_presensi'] = $item->jenis_presensi;
                $attr['puskesmas_id'] = $item->puskesmas_id;
                $attr['sekolah_id'] = $item->sekolah_id;

                Presensi::create($attr);
            } else {
            }
        }

        $com['nama_command'] = 'generate absensi android';
        $com['waktu_eksekusi'] = Carbon::now()->format('Y-m-d H:i:s');

        Komando::create($com);
    }
}
