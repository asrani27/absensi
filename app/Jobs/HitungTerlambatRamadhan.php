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

class HitungTerlambatRamadhan implements ShouldQueue
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
                            'terlambat' => 75,
                        ]);
                    } else {
                        $this->presensi->update([
                            'terlambat' => 240,
                        ]);
                    }
                } elseif (Pegawai::where('nip', $this->presensi->nip)->first()->jenis_presensi == 2) {
                    if (Carbon::parse($this->presensi->tanggal)->translatedFormat('l') == 'Jumat') {
                        $this->presensi->update([
                            'terlambat' => 90,
                        ]);
                    } elseif (Carbon::parse($this->presensi->tanggal)->translatedFormat('l') == 'Sabtu') {
                        $this->presensi->update([
                            'terlambat' => 165,
                        ]);
                    } else {
                        $this->presensi->update([
                            'terlambat' => 195,
                        ]);
                    }
                } else {
                }
            } elseif ($this->presensi->jam_masuk > $this->presensi->tanggal . ' ' . $this->jam->jam_masuk) {
                $terlambat = floor(Carbon::parse($this->presensi->jam_masuk)->diffInSeconds($this->presensi->tanggal . ' ' . $this->jam->jam_masuk) / 60);
                $this->presensi->update([
                    'terlambat' => $terlambat,
                ]);
            } else {
                $this->presensi->update([
                    'terlambat' => 0,
                ]);
            }

            if ($this->presensi->jam_pulang == null || Carbon::parse($this->presensi->jam_pulang)->format('H:i:s') == '00:00:00') {
                if (Pegawai::where('nip', $this->presensi->nip)->first()->jenis_presensi == 1) {
                    if (Carbon::parse($this->presensi->tanggal)->translatedFormat('l') == 'Jumat') {
                        $this->presensi->update([
                            'lebih_awal' => 75,
                        ]);
                    } else {
                        $this->presensi->update([
                            'lebih_awal' => 240,
                        ]);
                    }
                } elseif (Pegawai::where('nip', $this->presensi->nip)->first()->jenis_presensi == 2) {
                    if (Carbon::parse($this->presensi->tanggal)->translatedFormat('l') == 'Jumat') {
                        $this->presensi->update([
                            'lebih_awal' => 90,
                        ]);
                    } elseif (Carbon::parse($this->presensi->tanggal)->translatedFormat('l') == 'Sabtu') {
                        $this->presensi->update([
                            'lebih_awal' => 165,
                        ]);
                    } else {
                        $this->presensi->update([
                            'lebih_awal' => 195,
                        ]);
                    }
                } else {
                }
            } elseif ($this->presensi->jam_pulang < $this->presensi->tanggal . ' ' . $this->jam->jam_pulang) {
                $lebih_awal = floor(Carbon::parse($this->presensi->jam_pulang)->diffInSeconds($this->presensi->tanggal . ' ' . $this->jam->jam_pulang) / 60);
                $this->presensi->update([
                    'lebih_awal' => $lebih_awal,
                ]);
            } else {
                $this->presensi->update([
                    'lebih_awal' => 0,
                ]);
            }
        }
    }
}
