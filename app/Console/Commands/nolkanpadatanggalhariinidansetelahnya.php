<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Presensi;
use Illuminate\Console\Command;

class nolkanpadatanggalhariinidansetelahnya extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nolkanpadatanggalhariinidansetelahnya';

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
        $today = Carbon::now()->format('Y-m-d');
        $data = Presensi::where('tanggal', '>=', $today)->get();

        foreach ($data as $item) {
            $item->update([
                'terlambat' => 0,
                'lebih_awal' => 0,
            ]);
        }
    }
}
