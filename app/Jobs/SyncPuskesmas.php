<?php

namespace App\Jobs;

use App\Models\Presensi;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SyncPuskesmas implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $pegawai;

    public function __construct($item)
    {
        $this->pegawai = $item;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $presensi = Presensi::where('nip', $this->pegawai->nip)->get();
        foreach ($presensi as $p) {
            $p->update(['puskesmas_id' => $this->pegawai->puskesmas_id]);
        }
    }
}
