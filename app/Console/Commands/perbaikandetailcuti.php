<?php

namespace App\Console\Commands;

use App\Models\DetailCuti;
use Illuminate\Console\Command;

class perbaikandetailcuti extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'perbaikandetailcuti';

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
        $data = DetailCuti::get();
        foreach ($data as $d) {
            $check = DetailCuti::where('nip', $d->nip)->where('tanggal', $d->tanggal)->get();
            if (count($check) == 1) {
            } else {
                $d->delete();
            }
        }
    }
}
