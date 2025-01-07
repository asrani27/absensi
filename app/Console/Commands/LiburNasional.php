<?php

namespace App\Console\Commands;

use App\Models\Presensi;
use App\Models\LiburNasional;
use Illuminate\Console\Command;

class LiburNasional extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'liburnasional';

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
        $data = LiburNasional::get();
        foreach ($data as $item) {
            $check = Presensi::where('tanggal', $item->tanggal)->get();
            foreach ($check as $item2) {
                $item->update([
                    'jam_masuk' => $item->tanggal . ' 00:00:00',
                    'jam_pulang' => $item->tanggal . ' 00:00:00',
                    'terlambat' => 0,
                    'lebih_awal' => 0,
                    'jenis_keterangan_id' => null,
                ]);
            }
        }
    }
}
