<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Presensi;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class PresensiProcessPulang implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $pegawai;

    public function __construct()
    {
        $this->pegawai = Auth::user()->pegawai;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $date      = Carbon::now();
        $tanggal   = $date->format('Y-m-d');
        $jam_pulang = $date->format('H:i:s');

        $check     = Presensi::where('nip', $this->pegawai->nip)->where('tanggal', $tanggal)->first();

        if ($check == null) {
            $attr['nip']         = $this->pegawai->nip;
            $attr['nama']        = $this->pegawai->nama;
            $attr['tanggal']     = $tanggal;
            $attr['jam_pulang']  = $tanggal . ' ' . $jam_pulang;
            $attr['skpd_id']     = $this->pegawai->skpd_id;
            Presensi::create($attr);
        } else {
            $check->update([
                'jam_pulang' => $tanggal . ' ' . $jam_pulang,
                'skpd_id' => $this->pegawai->skpd_id,
            ]);
        }
    }
}
