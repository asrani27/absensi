<?php

namespace App\Console\Commands;

use App\Models\Presensi;
use Illuminate\Console\Command;

class HariBesarCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'haribesarcommand';

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
        $data = Presensi::where('tanggal', '2025-02-24')->get();
        foreach ($data as $item) {
            if ($item->jam_masuk_hari_besar != null) {
                $item->update([
                    'jam_masuk' => '2025-02-24 07:36:14',
                    'terlambat' => 0,
                ]);
            } else {
            }
        }
    }
}
