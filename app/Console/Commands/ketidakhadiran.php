<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ketidakhadiran extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ketidakhadiran';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'hitung ketidakhadiran';

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
        return 0;
    }
}
