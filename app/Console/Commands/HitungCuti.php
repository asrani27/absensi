<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Cuti;
use App\Models\Komando;
use App\Models\Pegawai;
use App\Models\Presensi;
use Carbon\CarbonPeriod;
use App\Models\DetailCuti;
use App\Models\LiburNasional;
use Illuminate\Console\Command;

class HitungCuti extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hitungcuti';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate cuti bulan ini';

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
        $data = DetailCuti::get();

        foreach ($data as $d) {
            $pegawai    = Pegawai::where('nip', $d->nip)->first();
            if ($pegawai->jenis_presensi == 1) {
                if (Carbon::parse($d->tanggal)->translatedFormat('l') == 'Minggu' || Carbon::parse($d->tanggal)->translatedFormat('l') == 'Sabtu') {
                    $presensi = Presensi::where('nip', $d->nip)->where('tanggal', $d->tanggal)->first();
                    if ($presensi != null) {
                        $presensi->update([
                            'terlambat' => 0,
                            'lebih_awal' => 0,
                            'jam_masuk' => 0,
                            'jam_pulang' => 0,
                            'jenis_keterangan_id' => null,
                        ]);
                    } else {
                    }
                } else {
                    $presensi = Presensi::where('nip', $d->nip)->where('tanggal', $d->tanggal)->first();
                    if ($presensi != null) {
                        $presensi->update([
                            'jam_masuk' => '00:00:00',
                            'jam_pulang' => '00:00:00',
                            'terlambat' => 0,
                            'lebih_awal' => 0,
                            'jenis_keterangan_id' => $d->jenis_keterangan_id,
                        ]);
                    } else {
                    }
                }
            } else {
                if (Carbon::parse($d->tanggal)->translatedFormat('l') == 'Minggu') {
                    $presensi = Presensi::where('nip', $d->nip)->where('tanggal', $d->tanggal)->first();
                    if ($presensi != null) {
                        $presensi->update([
                            'terlambat' => 0,
                            'lebih_awal' => 0,
                            'jam_masuk' => 0,
                            'jam_pulang' => 0,
                            'jenis_keterangan_id' => NULL,
                        ]);
                    } else {
                    }
                } else {
                    $presensi = Presensi::where('nip', $d->nip)->where('tanggal', $d->tanggal)->first();
                    if ($presensi != null) {
                        $presensi->update([
                            'jam_masuk' => '00:00:00',
                            'jam_pulang' => '00:00:00',
                            'terlambat' => 0,
                            'lebih_awal' => 0,
                            'jenis_keterangan_id' => $d->jenis_keterangan_id,
                        ]);
                    } else {
                    }
                }
            }
        }


        $com['nama_command'] = 'hitung cuti hari ini';
        $com['waktu_eksekusi'] = Carbon::now()->format('Y-m-d H:i:s');

        Komando::create($com);
    }
}
