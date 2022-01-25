<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Komando;
use App\Models\Presensi;
use App\Models\Ringkasan;
use Illuminate\Console\Command;

class RekapAbsensiBulanan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rekapbulanan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rekap Absensi Bulanan';

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
        $pegawai = Ringkasan::where('bulan', $bulan)->where('tahun', $tahun)->get();
        $pegawai->map(function ($item) use ($bulan, $tahun) {
            $presensi = Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();
            $item->datang_lambat = $presensi->sum('terlambat');
            $item->pulang_cepat = $presensi->sum('lebih_awal');
            $item->jam_kerja = round(($item->jumlah_jam - $item->datang_lambat - $item->pulang_cepat) / 60, 2);
            $item->persen_kehadiran = round(($item->jumlah_jam - $item->datang_lambat - $item->pulang_cepat) / $item->jumlah_jam * 100, 0);
            $item->save();
        });

        $com['nama_command'] = 'update rekap bulanan';
        $com['waktu_eksekusi'] = Carbon::now()->format('Y-m-d H:i:s');

        Komando::create($com);
    }
}
