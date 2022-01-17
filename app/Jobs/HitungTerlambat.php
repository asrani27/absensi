<?php

namespace App\Jobs;

use Carbon\Carbon;
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
        if ($this->presensi->jam_masuk == '00:00:00') {
            $this->presensi->update([
                'terlambat' => 240,
            ]);
        } elseif ($this->presensi->jam_masuk > $this->jam->jam_masuk) {
            $terlambat = floor(Carbon::parse($this->presensi->jam_masuk)->diffInSeconds($this->jam->jam_masuk) / 60);
            $this->presensi->update([
                'terlambat' => $terlambat,
            ]);
        } else {
            $this->presensi->update([
                'terlambat' => 0,
            ]);
        }

        if ($this->presensi->jam_pulang == '00:00:00') {
            $this->presensi->update([
                'lebih_awal' => 240,
            ]);
        } elseif ($this->presensi->jam_pulang < $this->jam->jam_pulang) {
            $lebih_awal = floor(Carbon::parse($this->presensi->jam_pulang)->diffInSeconds($this->jam->jam_pulang) / 60);
            //dd($lebih_awal, $item->jam_pulang, $jam->jam_pulang);
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
