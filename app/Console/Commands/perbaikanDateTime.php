<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Presensi;
use Illuminate\Console\Command;

class perbaikanDateTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fixdatetime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memperbaiki tanggal salah';

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
        $data = Presensi::get();
        foreach ($data as $item) {
            if ($item->jam_masuk == null) {
            } else {
                $item->update([
                    'jam_masuk' => $item->tanggal . ' ' . Carbon::parse($item->jam_masuk)->format('H:i:s'),
                ]);
            }
            if ($item->jam_pulang == null) {
            } else {
                $item->update([
                    'jam_pulang' => $item->tanggal . ' ' . Carbon::parse($item->jam_pulang)->format('H:i:s'),
                ]);
            }
        }
    }
}
