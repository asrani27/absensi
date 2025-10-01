<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Presensi;
use App\Models\Komando;
use App\Models\Pegawai;
use Illuminate\Console\Command;

class PerbaikanPresensi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'perbaikan:presensi {--tanggal=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memperbaiki presensi pulang untuk pegawai aktif';

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
        $this->info('Memulai perbaikan presensi pulang...');

        // Mendapatkan tanggal dari parameter atau menggunakan tanggal default 2025-09-01
        if ($this->option('tanggal') != null) {
            $tanggal = $this->option('tanggal');
        } else {
            $tanggal = '2025-09-01';
        }

        $this->info('Memproses tanggal: ' . $tanggal);

        // Mendapatkan semua pegawai aktif
        $pegawai = Pegawai::where('is_aktif', 1)->get();

        $this->info('Ditemukan ' . $pegawai->count() . ' pegawai aktif');

        $processed = 0;
        $fixed = 0;

        foreach ($pegawai as $item) {
            $processed++;

            // Mencari record presensi untuk pegawai pada tanggal tersebut
            $presensi = Presensi::where('nip', $item->nip)
                ->where('tanggal', $tanggal)
                ->first();

            if ($presensi) {
                // Logika perbaikan jam_pulang dengan kondisi tambahan:
                // 1. Hanya update jika jenis_keterangan_id NULL
                // 2. Hanya update jika jam_masuk bukan 2025-09-01 00:00:00
                // 3. Hanya update jika jenis_presensi = 1

                $shouldUpdate = true;

                // Kondisi 1: Jika jenis_keterangan_id tidak NULL, skip
                if ($presensi->jenis_keterangan_id != null) {
                    $shouldUpdate = false;
                    $this->line("Skip NIP: {$item->nip} - {$item->nama} (jenis_keterangan_id tidak NULL)");
                }

                // Kondisi 2: Jika jam_masuk = 2025-09-01 00:00:00, skip
                if ($shouldUpdate && $presensi->jam_masuk == '2025-09-01 00:00:00') {
                    $shouldUpdate = false;
                    $this->line("Skip NIP: {$item->nip} - {$item->nama} (jam_masuk = 2025-09-01 00:00:00)");
                }

                // Kondisi 3: Jika jenis_presensi != 1, skip
                if ($shouldUpdate && $presensi->jenis_presensi != 1) {
                    $shouldUpdate = false;
                    $this->line("Skip NIP: {$item->nip} - {$item->nama} (jenis_presensi != 1)");
                }

                // Jika semua kondisi terpenuhi, lakukan update
                if ($shouldUpdate) {
                    // Generate random jam_pulang antara 2025-09-01 16:35:01 s/d 2025-09-01 17:35:01
                    $startTime = Carbon::createFromFormat('Y-m-d H:i:s', '2025-09-01 16:35:01');
                    $endTime = Carbon::createFromFormat('Y-m-d H:i:s', '2025-09-01 17:35:01');

                    // Generate random timestamp dalam range tersebut
                    $randomTimestamp = Carbon::createFromTimestamp(
                        rand($startTime->timestamp, $endTime->timestamp)
                    );

                    $presensi->jam_pulang = $randomTimestamp->format('Y-m-d H:i:s');
                    $presensi->save();
                    $fixed++;

                    $this->line("Memperbaiki jam_pulang untuk NIP: {$item->nip} - {$item->nama} -> {$presensi->jam_pulang}");
                }
            } else {
            }
        }

        // Mencatat eksekusi command
        $com['nama_command'] = 'perbaikan presensi pulang';
        $com['waktu_eksekusi'] = Carbon::now()->format('Y-m-d H:i:s');
        $com['total_diproses'] = $processed;
        $com['total_diperbaiki'] = $fixed;

        Komando::create($com);

        $this->info('Perbaikan presensi selesai!');
        $this->info('Total pegawai diproses: ' . $processed);
        $this->info('Total presensi diperbaiki: ' . $fixed);

        return 0;
    }
}
