<?php

namespace App\Console\Commands;

use App\Models\PPPK;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ImportPPPKParuhWaktu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:pppk-paruh-waktu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data PPPK Paruh Waktu dari file Excel public/excel/paruh_waktu.xlsx';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filePath = public_path('excel/paruh_waktu.xlsx');

        if (!file_exists($filePath)) {
            $this->error('File Excel tidak ditemukan: ' . $filePath);
            return 1;
        }

        $this->info('Memulai import data dari file: ' . $filePath);
        $this->info('File berisi 3 sheet yang akan diproses...');

        try {
            // Load Excel file
            $excelData = Excel::toArray([], $filePath);

            if (empty($excelData)) {
                $this->error('File Excel kosong atau tidak dapat dibaca');
                return 1;
            }

            $totalSheets = count($excelData);
            $this->info('Jumlah sheet yang ditemukan: ' . $totalSheets);

            $totalProcessed = 0;
            $totalCreated = 0;
            $totalUpdated = 0;

            // Proses setiap sheet
            foreach ($excelData as $sheetIndex => $sheetData) {
                $sheetNumber = $sheetIndex + 1;
                $this->info("\n--- Memproses Sheet {$sheetNumber} ---");

                // Mulai dari baris ke-2 (index 1)
                $rowsToProcess = array_slice($sheetData, 1);

                $rowCount = count($rowsToProcess);
                $this->info("Jumlah baris data: {$rowCount}");

                if ($rowCount === 0) {
                    $this->warn("Sheet {$sheetNumber} tidak memiliki data, dilompati");
                    continue;
                }

                foreach ($rowsToProcess as $rowIndex => $row) {
                    $rowNumber = $rowIndex + 2; // Karena dimulai dari baris 2

                    // Mapping kolom sesuai request:
                    // NIP = kolom B (index 1)
                    // Nama = kolom C (index 2)
                    // SKPD = kolom D (index 3)
                    // Unit Kerja = kolom E (index 4)
                    // Jabatan = kolom F (index 5)

                    $nip = isset($row[1]) ? trim($row[1]) : null;
                    $nama = isset($row[2]) ? trim($row[2]) : null;
                    $skpd = isset($row[3]) ? trim($row[3]) : null;
                    $unit_kerja = isset($row[4]) ? trim($row[4]) : null;
                    $jabatan = isset($row[5]) ? trim($row[5]) : null;

                    // Validasi minimal ada NIP
                    if (empty($nip)) {
                        $this->warn("Sheet {$sheetNumber}, Baris {$rowNumber}: NIP kosong, dilompati");
                        continue;
                    }

                    // Cek apakah NIP sudah ada
                    $pppk = PPPK::where('nip', $nip)->first();

                    $data = [
                        'nip' => $nip,
                        'nama' => $nama,
                        'skpd' => $skpd,
                        'unit_kerja' => $unit_kerja,
                        'jabatan' => $jabatan,
                    ];

                    if ($pppk) {
                        // Update data yang sudah ada
                        $pppk->update($data);
                        $totalUpdated++;
                        $this->line("Sheet {$sheetNumber}, Baris {$rowNumber}: UPDATE - {$nip} - {$nama}");
                    } else {
                        // Buat data baru
                        PPPK::create($data);
                        $totalCreated++;
                        $this->line("Sheet {$sheetNumber}, Baris {$rowNumber}: CREATE - {$nip} - {$nama}");
                    }

                    $totalProcessed++;
                }

                $this->info("Sheet {$sheetNumber} selesai diproses");
            }

            // Summary
            $this->newLine();
            $this->info('=== IMPORT SELESAI ===');
            $this->info("Total data diproses: {$totalProcessed}");
            $this->info("Data baru dibuat: {$totalCreated}");
            $this->info("Data diperbarui: {$totalUpdated}");

            return 0;

        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan saat import: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }
    }
}