<?php

namespace App\Console\Commands;

use App\Models\DoubleData;
use App\Models\User;
use Illuminate\Console\Command;

class CheckDoubleData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'doubledata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Data Presensi Yang Double';

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
        $n = new DoubleData;
        $n->nip = 'asdasd';
        $n->tanggal = '2020-09-09';
        $n->save();
        $this->info($n);
    }
}
