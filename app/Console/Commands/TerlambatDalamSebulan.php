<?php

namespace App\Console\Commands;

use App\Models\Presensi;
use Illuminate\Console\Command;

class TerlambatDalamSebulan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hitungterlambatsebulan';

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
        $data = Presensi::whereMonth('tanggal', '01')->whereYear('tanggal', '2022')->get();
        dd($data->count());
    }
}
