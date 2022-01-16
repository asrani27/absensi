<?php

namespace App\Jobs;

use App\Models\Pegawai;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SyncUrut implements ShouldQueue
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
        $check = Pegawai::where('nip', $this->pegawai->nip)->first();
        if ($check == null) {
        } else {
            $check->update([
                'urutan'        => $this->pegawai->jabatan == null ? null : $this->pegawai->jabatan->kelas_id,
            ]);
        }
    }
}
