<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Jam;
use App\Models\Jam6;
use App\Models\Komando;
use App\Models\Pegawai;
use App\Models\Presensi;
use Carbon\CarbonPeriod;
use App\Models\ErrorData;
use App\Jobs\HitungTerlambat;
use App\Models\LiburNasional;
use Illuminate\Console\Command;

class HitungTerlambatHariIni extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'terlambat {--tanggal=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hitung Terlambat dan Pulang Lebih Awal Hari Ini';

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
        if ($this->option('tanggal') != null) {
            $tanggal = $this->option('tanggal');
        } else {
            $tanggal = Carbon::now()->format('Y-m-d');
        }

        $period = CarbonPeriod::create('2022-01-10', '2022-03-31');

        foreach ($period as $p) {
            $tanggal = $p->format('Y-m-d');

            $data = Presensi::where('tanggal', $tanggal)->get();

            foreach ($data as $item) {
                if (Pegawai::where('nip', $item->nip)->first() == null) {
                    $er = new ErrorData;
                    $er->nip = $item->nip;
                    $er->keterangan = 'menghitung terlambat tanggal ' . $tanggal;
                    $er->save();
                } else {
                    $checkJenisPresensi = Pegawai::where('nip', $item->nip)->first()->jenis_presensi;
                }
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
                            if ($item->jenis_keterangan_id == 7 || $item->jenis_keterangan_id == 5 || $item->jenis_keterangan_id == 9) {
                                $item->update([
                                    'terlambat' => 0,
                                    'lebih_awal' => 0,
                                    'jenis_keterangan_id' => $item->jenis_keterangan_id,
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
                            if ($item->jenis_keterangan_id == 7 || $item->jenis_keterangan_id == 5 || $item->jenis_keterangan_id == 9) {
                                $item->update([
                                    'terlambat' => 0,
                                    'lebih_awal' => 0,
                                    'jenis_keterangan_id' => $item->jenis_keterangan_id,
                                ]);
                            } else {
                                $hari = Carbon::parse($item->tanggal)->translatedFormat('l');
                                $jam = Jam6::where('hari', $hari)->first();
                                HitungTerlambat::dispatch($item, $jam);
                            }
                        }
                    }
                } else {
                    $item->update([
                        'terlambat' => 0,
                        'lebih_awal' => 0,
                    ]);
                }
            }

            $com['nama_command'] = 'hitung terlambat tanggal' . $tanggal;
            $com['waktu_eksekusi'] = Carbon::now()->format('Y-m-d H:i:s');

            Komando::create($com);
        }
    }
}
