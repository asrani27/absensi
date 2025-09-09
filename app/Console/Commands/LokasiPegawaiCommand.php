<?php

namespace App\Console\Commands;

use App\Models\Pegawai;
use App\Models\LokasiPegawai;
use Illuminate\Console\Command;

class LokasiPegawaiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lokasipegawaicommand';

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
        $this->info("Memulai proses update lokasi...");
        $p = Pegawai::select('id')->get();

        $existingPegawai = LokasiPegawai::where('lokasi_id', 1957)->pluck('pegawai_id')->toArray();
        $addedCount = 0;
        foreach ($p as $item) {
            if (!in_array($item->id, $existingPegawai)) {
                LokasiPegawai::create([
                    'pegawai_id' => $item->id,
                    'lokasi_id' => 1957
                ]);
                $addedCount++;
            }
        }
        if ($addedCount > 0) {
            $this->info("Proses selesai! $addedCount pegawai berhasil ditambahkan.");
        } else {
            $this->warn("Tidak ada pegawai baru yang perlu ditambahkan.");
        }
    }
}
