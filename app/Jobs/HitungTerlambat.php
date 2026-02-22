<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Pegawai;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class HitungTerlambat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $presensi;
    public $jam;

    public function __construct($item, $jam)
    {
        $this->presensi = $item;
        $this->jam = $jam;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (Pegawai::where('nip', $this->presensi->nip)->first() == null) {
        } else {
            if ($this->presensi->jam_masuk == null || Carbon::parse($this->presensi->jam_masuk)->format('H:i:s') == '00:00:00') {
                if (Pegawai::where('nip', $this->presensi->nip)->first()->jenis_presensi == 1) {
                    if (Carbon::parse($this->presensi->tanggal)->translatedFormat('l') == 'Jumat') {
                        $this->presensi->update([
                            'terlambat' => 105,
                            'denda_terlambat' => 1.5,
                        ]);
                    } else {
                        $this->presensi->update([
                            'terlambat' => 255,
                            'denda_terlambat' => 1.5,
                        ]);
                    }
                } elseif (Pegawai::where('nip', $this->presensi->nip)->first()->jenis_presensi == 2) {
                    if (Carbon::parse($this->presensi->tanggal)->translatedFormat('l') == 'Jumat') {
                        $this->presensi->update([
                            'terlambat' => 105,
                            'denda_terlambat' => 1.5,
                        ]);
                    } elseif (Carbon::parse($this->presensi->tanggal)->translatedFormat('l') == 'Sabtu') {
                        $this->presensi->update([
                            'terlambat' => 180,
                            'denda_terlambat' => 1.5,
                        ]);
                    } else {
                        $this->presensi->update([
                            'terlambat' => 210,
                        ]);
                    }
                } else {
                }
            } elseif ($this->presensi->jam_masuk > $this->presensi->tanggal . ' ' . $this->jam->jam_masuk) {
                $terlambat = floor(Carbon::parse($this->presensi->jam_masuk)->diffInSeconds($this->presensi->tanggal . ' ' . $this->jam->jam_masuk) / 60);

                if ($terlambat >= 1 && $terlambat <= 30) {
                    $denda_terlambat = 0.5;
                } elseif ($terlambat >= 31 && $terlambat <= 60) {
                    $denda_terlambat = 1;
                } elseif ($terlambat >= 61 && $terlambat <= 90) {
                    $denda_terlambat = 1.25;
                } elseif ($terlambat >= 91) {
                    $denda_terlambat = 1.50;
                } else {
                    $denda_terlambat = 0;
                }
                $this->presensi->update([
                    'terlambat' => $terlambat,
                    'denda_terlambat' => $denda_terlambat,
                ]);
            } else {
                $this->presensi->update([
                    'terlambat' => 0,
                    'denda_terlambat' => 0,
                ]);
            }

            if ($this->presensi->jam_pulang == null || Carbon::parse($this->presensi->jam_pulang)->format('H:i:s') == '00:00:00') {
                if (Pegawai::where('nip', $this->presensi->nip)->first()->jenis_presensi == 1) {
                    if (Carbon::parse($this->presensi->tanggal)->translatedFormat('l') == 'Jumat') {
                        $this->presensi->update([
                            'lebih_awal' => 105,
                            'denda_lebih_awal' => 1.5,
                        ]);
                    } else {
                        $this->presensi->update([
                            'lebih_awal' => 255,
                            'denda_lebih_awal' => 1.5,
                        ]);
                    }
                } elseif (Pegawai::where('nip', $this->presensi->nip)->first()->jenis_presensi == 2) {
                    if (Carbon::parse($this->presensi->tanggal)->translatedFormat('l') == 'Jumat') {
                        $this->presensi->update([
                            'lebih_awal' => 105,
                            'denda_lebih_awal' => 1.5,
                        ]);
                    } elseif (Carbon::parse($this->presensi->tanggal)->translatedFormat('l') == 'Sabtu') {
                        $this->presensi->update([
                            'lebih_awal' => 180,
                            'denda_lebih_awal' => 1.5,
                        ]);
                    } else {
                        $this->presensi->update([
                            'lebih_awal' => 210,
                            'denda_lebih_awal' => 1.5,
                        ]);
                    }
                } else {
                }
            } elseif ($this->presensi->jam_pulang < $this->presensi->tanggal . ' ' . $this->jam->jam_pulang) {
                $lebih_awal = floor(Carbon::parse($this->presensi->jam_pulang)->diffInSeconds($this->presensi->tanggal . ' ' . $this->jam->jam_pulang) / 60);
                if ($lebih_awal >= 1 && $lebih_awal <= 30) {
                    $denda_lebih_awal = 0.5;
                } elseif ($lebih_awal >= 31 && $lebih_awal <= 60) {
                    $denda_lebih_awal = 1;
                } elseif ($lebih_awal >= 61 && $lebih_awal <= 90) {
                    $denda_lebih_awal = 1.25;
                } elseif ($lebih_awal >= 91) {
                    $denda_lebih_awal = 1.50;
                } else {
                    $denda_lebih_awal = 0;
                }
                $this->presensi->update([
                    'lebih_awal' => $lebih_awal,
                    'denda_lebih_awal' => $denda_lebih_awal,
                ]);
            } else {
                $this->presensi->update([
                    'lebih_awal' => 0,
                    'denda_lebih_awal' => 0,
                ]);
            }
        }
    }
}
