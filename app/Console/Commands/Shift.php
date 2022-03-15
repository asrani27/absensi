<?php

namespace App\Console\Commands;

use App\Models\Pegawai;
use App\Models\Presensi;
use Illuminate\Console\Command;

class Shift extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shift';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '0 kan yang shift';

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
        //Get pEgawai Shift
        $pegawaiShift = Pegawai::where('jenis_presensi', 3)->get();
        foreach ($pegawaiShift as $p) {
            $aktivitas = Presensi::where('nip', $p->nip)->get();
            foreach ($aktivitas as $a) {
                $a->update([
                    'terlambat' => 0,
                    'lebih_awal' => 0,
                ]);
            }
        }
    }
}
