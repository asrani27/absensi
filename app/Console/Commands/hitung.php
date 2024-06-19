<?php

namespace App\Console\Commands;

use App\Models\Hitung as ModelsHitung;
use App\Models\Pegawai;
use App\Models\Presensi;
use Illuminate\Console\Command;

class hitung extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hitungterlambat';

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
        $data = Pegawai::orderBy('nama', 'asc')->get()->map(function ($item) {
            $item->terlambat = Presensi::where('nip', $item->nip)->whereYear('tanggal', '2023')->sum('terlambat');
            $item->lebih_awal = Presensi::where('nip', $item->nip)->whereYear('tanggal', '2023')->sum('lebih_awal');
            return $item;
        });
        foreach ($data as $item) {
            $h = new ModelsHitung();
            $h->nip = $item->nip;
            $h->nama = $item->nama;
            $h->terlambat = $item->terlambat;
            $h->lebih_awal = $item->lebih_awal;
            $h->save();
        }
        return 'sip';
    }
}
