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
        Pegawai::where('status_asn', 'PPPK')->where('skpd_id', 14)->get()->each(function ($item) {
            if ($item->user) {
                $item->user->delete();
            }
        });
        Pegawai::where('status_asn', 'PPPK')->where('skpd_id', 14)->delete();
        $kominfo = public_path('excel/PPPK_kominfo.xlsx');
        Excel::import(new PPPK($this), $kominfo);
    }
}
