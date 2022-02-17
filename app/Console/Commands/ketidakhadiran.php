<?php

namespace App\Console\Commands;

use App\Models\Presensi;
use App\Models\Ringkasan;
use Illuminate\Console\Command;

class ketidakhadiran extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ketidakhadiran';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'hitung ketidakhadiran';

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
        $ringkasan = Ringkasan::where('bulan', '01')->where('tahun', '2022')->get();
        foreach ($ringkasan as $item) {

            $countSakit = count(Presensi::whereMonth('tanggal', '02')->whereYear('tanggal', '2022')->where('jenis_keterangan_id', 3)->get());
            $countSakitKarenaCovid = count(Presensi::whereMonth('tanggal', '02')->whereYear('tanggal', '2022')->where('jenis_keterangan_id', 9)->get());
            $countCutiTahun = count(Presensi::whereMonth('tanggal', '02')->whereYear('tanggal', '2022')->where('jenis_keterangan_id', 7)->get());
            $countCutiLain = count(Presensi::whereMonth('tanggal', '02')->whereYear('tanggal', '2022')->where('jenis_keterangan_id', 8)->get());
            $countTraining = count(Presensi::whereMonth('tanggal', '02')->whereYear('tanggal', '2022')->where('jenis_keterangan_id', 4)->get());
            $countTugas = count(Presensi::whereMonth('tanggal', '02')->whereYear('tanggal', '2022')->where('jenis_keterangan_id', 5)->get());

            $item->update([
                's' => $countSakit,
                'tr' => $countTraining,
                'd' => $countTugas,
                'c' => $countCutiTahun,
                'l' => $countCutiLain
            ]);
        }
    }
}
