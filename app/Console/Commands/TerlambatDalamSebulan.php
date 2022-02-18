<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Jam;
use App\Models\Jam6;
use App\Models\Komando;
use App\Models\Pegawai;
use App\Models\Presensi;
use App\Jobs\HitungTerlambat;
use App\Models\LiburNasional;
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
        //Hitung Terlambat Untuk Jenis Presensi 5 Hari Kerja
        $data = Presensi::where('skpd_id', '14')->whereMonth('tanggal', '01')->whereYear('tanggal', '2022')->get();

        foreach ($data as $item) {
            $checkJenisPresensi = Pegawai::where('nip', $item->nip)->first()->jenis_presensi;
            //cek dia jenis presensi 5 hari kerja gak?
            if ($checkJenisPresensi == 1) {
                //cek dia tanggalnya weekend gak?
                if (Carbon::parse($item->tanggal)->isWeekend() == true) {
                    // weekend 
                    $item->update([
                        'terlambat' => 0,
                        'lebih_awal' => 0,
                    ]);
                } else {
                    //cek dia tanggalnya Libur nasional gak?
                    if (LiburNasional::where('tanggal', $item->tanggal)->first() != null) {
                        $item->update([
                            'terlambat' => 0,
                            'lebih_awal' => 0,
                        ]);
                    } else {
                        //cek dia TL / Cuti Tahunan gak?
                        if ($item->jenis_keterangan_id == 7 || $item->jenis_keterangan_id == 5 || $item->jenis_keterangan_id == 9 || $item->jenis_keterangan_id == 4) {
                            $item->update([
                                'terlambat' => 0,
                                'lebih_awal' => 0,
                            ]);
                        } else {
                            $hari = Carbon::parse($item->tanggal)->translatedFormat('l');
                            $jam = Jam::where('hari', $hari)->first();
                            HitungTerlambat::dispatch($item, $jam);
                        }
                    }
                }
            } elseif ($checkJenisPresensi == 2) {
                //cek dia tanggalnya minggu gak?
                if (Carbon::parse($item->tanggal)->translatedFormat('l') == 'Minggu') {
                    // minggu 
                    $item->update([
                        'terlambat' => 0,
                        'lebih_awal' => 0,
                    ]);
                } else {
                    //cek dia tanggalnya Libur nasional gak?
                    if (LiburNasional::where('tanggal', $item->tanggal)->first() != null) {
                        $item->update([
                            'terlambat' => 0,
                            'lebih_awal' => 0,
                        ]);
                    } else {
                        //cek dia TL / Cuti Tahunan gak?
                        if ($item->jenis_keterangan_id == 7 || $item->jenis_keterangan_id == 5 || $item->jenis_keterangan_id == 9 || $item->jenis_keterangan_id == 4) {
                            $item->update([
                                'terlambat' => 0,
                                'lebih_awal' => 0,
                            ]);
                        } else {
                            $hari = Carbon::parse($item->tanggal)->translatedFormat('l');
                            $jam = Jam6::where('hari', $hari)->first();
                            HitungTerlambat::dispatch($item, $jam);
                        }
                    }
                }
            } else {
            }
        }

        $com['nama_command'] = 'perbaikan data terlambat dan lebih awal Bulan Januari 2022 utk presensi 5 hari kerja karena edit oleh admin';
        $com['waktu_eksekusi'] = Carbon::now()->format('Y-m-d H:i:s');

        Komando::create($com);
    }
}
