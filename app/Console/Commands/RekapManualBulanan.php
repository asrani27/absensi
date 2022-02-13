<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Komando;
use App\Models\Pegawai;
use App\Models\Presensi;
use App\Models\Ringkasan;
use Illuminate\Console\Command;

class RekapManualBulanan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rekapmanualbulanan';

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
        $pegawai = Ringkasan::where('bulan', '01')->where('tahun', '2022')->get();
        $pegawai->map(function ($item) {
            try {
                $checkJenisPresensi = Pegawai::where('nip', $item->nip)->first()->jenis_presensi;
                //cek dia jenis presensi 5 hari kerja gak?
                if ($checkJenisPresensi == 1) {
                    $presensi = Presensi::where('nip', $item->nip)->whereMonth('tanggal', '01')->whereYear('tanggal', '2022')->get();
                    $item->datang_lambat = $presensi->sum('terlambat');
                    $item->pulang_cepat = $presensi->sum('lebih_awal');
                    $item->jam_kerja = round(($item->jumlah_jam - $item->datang_lambat - $item->pulang_cepat) / 60, 2);
                    $item->persen_kehadiran = round(($item->jumlah_jam - $item->datang_lambat - $item->pulang_cepat) / $item->jumlah_jam * 100, 2);
                    $item->save();
                } else {
                }
            } catch (\Exception $e) {
                dd($item);
            }
        });

        $com['nama_command'] = 'update rekap bulanan manual perbaikan data bulan januari 2022';
        $com['waktu_eksekusi'] = Carbon::now()->format('Y-m-d H:i:s');

        Komando::create($com);
    }
}
