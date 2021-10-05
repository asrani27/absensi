<?php

namespace App\Jobs;

use App\Models\Pegawai;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SyncPegawai implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    
    public $pegawai;
    public $skpd;

    public function __construct($item)
    {
        $this->pegawai = $item;
        $this->skpd = Auth::user()->skpd;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    
    public function handle()
    {
        $check = Pegawai::where('nip', $this->pegawai->nip)->first();
        if($check == null){
            //simpan data
            $p = new Pegawai;
            $p->nip = $this->pegawai->nip;
            $p->nama = $this->pegawai->nama == null ? null : $this->pegawai->nama;
            $p->jabatan = $this->pegawai->jabatan == null ? null : $this->pegawai->jabatan->nama;
            $p->tanggal_lahir = $this->pegawai->tanggal_lahir;
            $p->skpd_id = $this->skpd->id;
            $p->is_aktif = $this->pegawai->is_aktif;
            $p->save();                            
        }else{
            $check->update([
                'jabatan' => $this->pegawai->jabatan == null ? null: $this->pegawai->jabatan->nama,
                'skpd_id' => $this->skpd->id,
                'tanggal_lahir' => $this->pegawai->tanggal_lahir,
                'nama' => $this->pegawai->nama,
            ]);
        }
    }
}
