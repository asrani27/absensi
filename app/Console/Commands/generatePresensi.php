<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Komando;
use App\Models\Pegawai;
use App\Models\Presensi;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;

class generatePresensi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'presensi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Presensi Pegawai';

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
        $pegawai = Pegawai::where('is_aktif', 1)->get();
        $today = Carbon::today()->format('Y-m-d');

        foreach ($pegawai as $item) {
            $p = Presensi::where('nip', $item->nip)->where('tanggal', $today)->first();
            if ($p == null) {
                $attr['nip'] = $item->nip;
                $attr['nama'] = $item->nama;
                $attr['tanggal'] = $today;
                $attr['jam_masuk'] = $today . ' 00:00:00';
                $attr['jam_pulang'] = $today . ' 00:00:00';
                $attr['skpd_id'] = $item->skpd_id;
                $attr['jenis_presensi'] = $item->jenis_presensi;
                $attr['puskesmas_id'] = $item->puskesmas_id;
                $attr['sekolah_id'] = $item->sekolah_id;

                Presensi::create($attr);
            } else {
                // if ($p->jam_masuk == null) {
                //     $p->update(['jam_masuk' => '00:00:00']);
                // }

                // if ($p->jam_pulang == null) {
                //     $p->update(['jam_pulang' => '00:00:00']);
                // }

                if ($p->skpd_id == null) {
                    $p->update(['skpd_id' => $item->skpd_id]);
                }

                if ($p->jenis_presensi == null) {
                    $p->update(['jenis_presensi' => $item->jenis_presensi]);
                }
            }
        }

        $com['nama_command'] = 'presensi';
        $com['waktu_eksekusi'] = Carbon::now()->format('Y-m-d H:i:s');

        Komando::create($com);
    }
}
