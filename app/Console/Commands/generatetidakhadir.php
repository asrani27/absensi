<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Presensi;
use Illuminate\Console\Command;

class generatetidakhadir extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tidakhadir  {--bulan=} {--tahun=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate tidak hadir';

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
        if ($this->option('bulan') != null) {
            $month = $this->option('bulan');
            $year = $this->option('tahun');
        } else {
            $month = Carbon::now()->month;
            $year = Carbon::now()->year;
        }

        $data = Presensi::whereMonth('tanggal', $month)->whereYear('tanggal', $year)->get();
        foreach ($data as $d) {
            $masuk = Carbon::parse($d->jam_masuk)->format('H:i');
            $pulang = Carbon::parse($d->jam_pulang)->format('H:i');
            if ($masuk == '00:00' && $pulang == '00:00') {
                $d->update([
                    'terlambat' => 0,
                    'lebih_awal' => 0,
                ]);
            }
        }
    }
}
