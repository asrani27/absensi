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
        $pegawai = User::where('android_id', null)->get();
        foreach ($pegawai as $key => $item) {
            $absen = Presensi::where('tanggal', '2023-03-09')->where('nip', $item->username)->first();
            if ($absen == null) {
            } else {
                $absen->update([
                    'jam_masuk' => '2023-03-09 07:54:32',
                ]);
            }
        }

        return 'sukses';
    }
}
