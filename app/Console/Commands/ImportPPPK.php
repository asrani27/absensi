<?php

namespace App\Console\Commands;

use App\Imports\PPPK;
use App\Models\Pegawai;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ImportPPPK extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:pppk';

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
        // Pegawai::where('status_asn', 'PPPK')->get()->each(function ($item) {
        //     if ($item->user) {
        //         $item->user->delete();
        //     }
        //     $item->lokasiPegawai()->delete();
        // });
        // Pegawai::where('status_asn', 'PPPK')->delete();
        $skpd = public_path('excel/PPPK_ID.xlsx');
        Excel::import(new PPPK($this), $skpd);
    }
}
