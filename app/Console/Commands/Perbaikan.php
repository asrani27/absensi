<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Presensi;
use Illuminate\Console\Command;

class Perbaikan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'perbaikanabsen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perbaikan Absen Versi Web';

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
        $presensi = Presensi::where('tanggal', '2024-01-25')->get();

        foreach ($presensi as $key => $item) {
            if ($item->jenis_keterangan_id == null) {
                $item->update([
                    'jam_masuk' => "2024-01-25 07:10:01"
                ]);
            } else {
            }
        }
        return 'sukses';
    }
}
