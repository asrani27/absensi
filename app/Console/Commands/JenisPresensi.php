<?php

namespace App\Console\Commands;

use App\Models\Pegawai;
use App\Models\Presensi;
use App\Models\Ringkasan;
use Illuminate\Console\Command;

class JenisPresensi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jenispresensi';

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
        $data = Pegawai::get();
        foreach ($data as $item) {
            $presensi = Presensi::whereMonth('tanggal', '01')->whereYear('tanggal', '2022')->where('nip', $item->nip)->get();
            foreach ($presensi as $item2) {
                $item2->update(['jenis_presensi' => $item->jenis_presensi]);
            }
        }
    }
}
