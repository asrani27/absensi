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
use Illuminate\Console\Command;

class PerbaikanCuti extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'perbaikancuti';

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
        $cuti = Cuti::whereMonth('tanggal_mulai', '09')->whereYear('tanggal_mulai', '2022')->get();

        foreach ($cuti as $c) {
            $period = CarbonPeriod::create($c->tanggal_mulai, $c->tanggal_selesai);
            foreach ($period as $p) {
                $check = DetailCuti::where('nip', $c->nip)->where('tanggal', $p->format('Y-m-d'))->first();
                if ($check == null) {
                    //isi baru
                    $n = new DetailCuti;
                    $n->cuti_id             = $c->id;
                    $n->nip                 = $c->nip;
                    $n->skpd_id             = $c->skpd_id;
                    $n->tanggal             = $p->format('Y-m-d');
                    $n->jenis_keterangan_id = $c->jenis_keterangan_id;
                    $n->save();
                } else {
                }
            }
        }

        $com['nama_command'] = 'Perbaikan Cuti Pada hari Sabtu';
        $com['waktu_eksekusi'] = Carbon::now()->format('Y-m-d H:i:s');

        Komando::create($com);
        
        // $detailCuti = DetailCuti::get();
        // foreach ($detailCuti as $d) {
        //     $check = Presensi::where('nip', $d->nip)->where('tanggal', $d->tanggal)->first();
        //     if ($check == null) {
        //     } else {
        //         if ($d->jenis_keterangan_id == 7 || $d->jenis_keterangan_id == 5 || $d->jenis_keterangan_id == 9) {
        //             $check->update([
        //                 'terlambat' => 0,
        //                 'lebih_awal' => 0,
        //                 'jenis_keterangan_id' => $d->jenis_keterangan_id,
        //             ]);
        //         } else {
        //             $hari = Carbon::parse($d->tanggal)->translatedFormat('l');
        //             $pegawai = Pegawai::where('nip', $d->nip)->first();
        //             if ($pegawai->jenis_presensi == 1) {
        //                 $jam = Jam::where('hari', $hari)->first();
        //             } elseif ($pegawai->jenis_presensi == 2) {
        //                 $jam = Jam6::where('hari', $hari)->first();
        //             } else {
        //             }

        //             if ($pegawai->jenis_presensi == 1) {
        //             } elseif ($pegawai->jenis_presensi == 2) {
        //                 $check->update([
        //                     'terlambat' => 0,
        //                     'lebih_awal' => 0,
        //                     'jenis_keterangan_id' => $d->jenis_keterangan_id,
        //                 ]);
        //             }

        //             // $presensi = $check;
        //             // if ($presensi->jam_masuk == '00:00:00') {
        //             //     if (Pegawai::where('nip', $presensi->nip)->first()->jenis_presensi == 1) {
        //             //         if (Carbon::parse($presensi->tanggal)->translatedFormat('l') == 'Jumat') {
        //             //             $presensi->update([
        //             //                 'terlambat' => 105,
        //             //             ]);
        //             //         } else {
        //             //             $presensi->update([
        //             //                 'terlambat' => 255,
        //             //             ]);
        //             //         }
        //             //     } elseif (Pegawai::where('nip', $presensi->nip)->first()->jenis_presensi == 2) {
        //             //         if (Carbon::parse($presensi->tanggal)->translatedFormat('l') == 'Jumat') {
        //             //             $presensi->update([
        //             //                 'terlambat' => 105,
        //             //             ]);
        //             //         } elseif (Carbon::parse($presensi->tanggal)->translatedFormat('l') == 'Sabtu') {
        //             //             $presensi->update([
        //             //                 'terlambat' => 180,
        //             //             ]);
        //             //         } else {
        //             //             $presensi->update([
        //             //                 'terlambat' => 210,
        //             //             ]);
        //             //         }
        //             //     } else {
        //             //     }
        //             // } elseif ($presensi->jam_masuk > $jam->jam_masuk) {
        //             //     $terlambat = floor(Carbon::parse($presensi->jam_masuk)->diffInSeconds($jam->jam_masuk) / 60);
        //             //     $presensi->update([
        //             //         'terlambat' => $terlambat,
        //             //     ]);
        //             // } else {
        //             //     $presensi->update([
        //             //         'terlambat' => 0,
        //             //     ]);
        //             // }

        //             // if ($presensi->jam_pulang == '00:00:00') {
        //             //     if (Pegawai::where('nip', $presensi->nip)->first()->jenis_presensi == 1) {
        //             //         if (Carbon::parse($presensi->tanggal)->translatedFormat('l') == 'Jumat') {
        //             //             $presensi->update([
        //             //                 'lebih_awal' => 105,
        //             //             ]);
        //             //         } else {
        //             //             $presensi->update([
        //             //                 'lebih_awal' => 255,
        //             //             ]);
        //             //         }
        //             //     } elseif (Pegawai::where('nip', $presensi->nip)->first()->jenis_presensi == 2) {
        //             //         if (Carbon::parse($presensi->tanggal)->translatedFormat('l') == 'Jumat') {
        //             //             $presensi->update([
        //             //                 'lebih_awal' => 105,
        //             //             ]);
        //             //         } elseif (Carbon::parse($presensi->tanggal)->translatedFormat('l') == 'Sabtu') {
        //             //             $presensi->update([
        //             //                 'lebih_awal' => 180,
        //             //             ]);
        //             //         } else {
        //             //             $presensi->update([
        //             //                 'lebih_awal' => 210,
        //             //             ]);
        //             //         }
        //             //     } else {
        //             //     }
        //             // } elseif ($presensi->jam_pulang < $jam->jam_pulang) {
        //             //     $lebih_awal = floor(Carbon::parse($presensi->jam_pulang)->diffInSeconds($jam->jam_pulang) / 60);
        //             //     $presensi->update([
        //             //         'lebih_awal' => $lebih_awal,
        //             //     ]);
        //             // } else {
        //             //     $presensi->update([
        //             //         'lebih_awal' => 0,
        //             //     ]);
        //             // }
        //         }
        //     }
        // }
    }
}
