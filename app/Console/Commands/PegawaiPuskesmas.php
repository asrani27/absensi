<?php

namespace App\Console\Commands;

use App\Models\Pegawai;
use App\Models\Ringkasan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class PegawaiPuskesmas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pegawaipuskesmas';

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
        $data = Pegawai::where('puskesmas_id', '!=', null)->get();
        foreach ($data as $item) {
            $check = Ringkasan::where('nip', $item->nip)->get();
            if (count($check) == 0) {
                //buat baru bulan januari
                $n = new Ringkasan;
                $n->nip = $item->nip;
                $n->nama = $item->nama;
                $n->jabatan = $item->jabatan;
                $n->skpd_id = Auth::user()->skpd->id;
                $n->bulan = '01';
                $n->tahun = '2022';
                $n->puskesmas_id = $item->puskesmas_id;
                $n->save();
            } else {
                foreach ($check as $item2) {
                    $item2->update([
                        'puskesmas_id' => $item->puskesmas_id
                    ]);
                }
            }
        }
    }
}
