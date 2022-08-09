<?php

namespace App\Console\Commands;

use App\Models\Presensi;
use Illuminate\Console\Command;

class generateNULL extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generatenull';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengisi data Yang Null';

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
        $masuk = Presensi::where('jam_masuk', null)->get();
        $pulang = Presensi::where('jam_pulang', null)->get();

        foreach ($masuk as $m) {
            $m->update([
                'jam_masuk' => $m->tanggal . ' 00:00:00',
            ]);
        }
        foreach ($pulang as $p) {
            $p->update([
                'jam_pulang' => $p->tanggal . ' 00:00:00',
            ]);
        }
    }
}
