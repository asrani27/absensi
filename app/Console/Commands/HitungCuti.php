<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Jam;
use App\Models\Cuti;
use App\Models\Jam6;
use App\Models\Komando;
use App\Models\Pegawai;
use App\Models\Presensi;
use Carbon\CarbonPeriod;
use App\Models\DetailCuti;
use App\Jobs\HitungTerlambat;
use App\Models\LiburNasional;
use Illuminate\Console\Command;

class HitungCuti extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hitungcuti';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate cuti bulan ini';

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
        $month = Carbon::now()->month();
        $year = Carbon::now()->year();
        $data = DetailCuti::whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
        dd($data, $month, $year);
        //$data = DetailCuti::where('jenis_keterangan_id', 4)->get();

        foreach ($data as $d) {
            $pegawai    = Pegawai::where('nip', $d->nip)->first();
            if ($pegawai->jenis_presensi == 1) {
                if (Carbon::parse($d->tanggal)->translatedFormat('l') == 'Minggu' || Carbon::parse($d->tanggal)->translatedFormat('l') == 'Sabtu') {
                    $presensi = Presensi::where('nip', $d->nip)->where('tanggal', $d->tanggal)->first();
                    if ($presensi != null || LiburNasional::where('tanggal', $d->tanggal)->first() != null) {
                        $presensi->update([
                            'terlambat' => 0,
                            'lebih_awal' => 0,
                            'jam_masuk' => $d->tanggal . ' 00:00:00',
                            'jam_pulang' => $d->tanggal . ' 00:00:00',
                            'jenis_keterangan_id' => null,
                        ]);
                    } else {
                    }
                } else {
                    $presensi = Presensi::where('nip', $d->nip)->where('tanggal', $d->tanggal)->first();
                    if ($presensi != null) {
                        if (LiburNasional::where('tanggal', $d->tanggal)->first() != null) {
                            $presensi->update([
                                'terlambat' => 0,
                                'lebih_awal' => 0,
                                'jam_masuk' => '00:00:00',
                                'jam_pulang' => '00:00:00',
                                'jenis_keterangan_id' => null,
                            ]);
                        } else {
                            if ($d->jenis_keterangan_id == 7 || $d->jenis_keterangan_id == 5 || $d->jenis_keterangan_id == 9 || $d->jenis_keterangan_id == 4) {
                                $presensi->update([
                                    'jam_masuk' => $d->tanggal . ' 00:00:00',
                                    'jam_pulang' => $d->tanggal . ' 00:00:00',
                                    'terlambat' => 0,
                                    'lebih_awal' => 0,
                                    'jenis_keterangan_id' => $d->jenis_keterangan_id,
                                ]);
                            } else {
                                $presensi->update([
                                    'jam_masuk' => $d->tanggal . ' 00:00:00',
                                    'jam_pulang' => $d->tanggal . ' 00:00:00',
                                    'jenis_keterangan_id' => $d->jenis_keterangan_id,
                                ]);
                                $hari = Carbon::parse($d->tanggal)->translatedFormat('l');
                                $jam = Jam::where('hari', $hari)->first();
                                HitungTerlambat::dispatch($presensi, $jam);
                            }
                        }
                    } else {
                    }
                }
            } elseif ($pegawai->jenis_presensi == 2) {
                if (Carbon::parse($d->tanggal)->translatedFormat('l') == 'Minggu') {
                    $presensi = Presensi::where('nip', $d->nip)->where('tanggal', $d->tanggal)->first();
                    if ($presensi != null || LiburNasional::where('tanggal', $d->tanggal)->first() != null) {
                        $presensi->update([
                            'terlambat' => 0,
                            'lebih_awal' => 0,
                            'jam_masuk' => $d->tanggal . ' 00:00:00',
                            'jam_pulang' => $d->tanggal . ' 00:00:00',
                            'jenis_keterangan_id' => null,
                        ]);
                    } else {
                    }
                } else {
                    $presensi = Presensi::where('nip', $d->nip)->where('tanggal', $d->tanggal)->first();
                    if ($presensi != null) {
                        if (LiburNasional::where('tanggal', $d->tanggal)->first() != null) {
                            $presensi->update([
                                'terlambat' => 0,
                                'lebih_awal' => 0,
                                'jam_masuk' => $d->tanggal . ' 00:00:00',
                                'jam_pulang' => $d->tanggal . ' 00:00:00',
                                'jenis_keterangan_id' => null,
                            ]);
                        } else {
                            if ($d->jenis_keterangan_id == 7 || $d->jenis_keterangan_id == 5 || $d->jenis_keterangan_id == 9 || $d->jenis_keterangan_id == 4) {
                                $presensi->update([
                                    'jam_masuk' => $d->tanggal . ' 00:00:00',
                                    'jam_pulang' => $d->tanggal . ' 00:00:00',
                                    'terlambat' => 0,
                                    'lebih_awal' => 0,
                                    'jenis_keterangan_id' => $d->jenis_keterangan_id,
                                ]);
                            } else {
                                $presensi->update([
                                    'jam_masuk' => $d->tanggal . ' 00:00:00',
                                    'jam_pulang' => $d->tanggal . ' 00:00:00',
                                    'jenis_keterangan_id' => $d->jenis_keterangan_id,
                                ]);
                                $hari = Carbon::parse($d->tanggal)->translatedFormat('l');
                                $jam = Jam6::where('hari', $hari)->first();
                                HitungTerlambat::dispatch($presensi, $jam);
                            }
                        }
                    } else {
                    }
                }
            } else {
                //Presensi Jenis SHIFT
            }

            $d->update(['validasi' => 1]);
        }


        $com['nama_command'] = 'hitung cuti hari ini';
        $com['waktu_eksekusi'] = Carbon::now()->format('Y-m-d H:i:s');

        Komando::create($com);
    }
}
