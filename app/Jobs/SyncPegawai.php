<?php

namespace App\Jobs;

use App\Models\Pegawai;
use App\Models\Ringkasan;
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
        if ($check == null) {
            //simpan data
            $p = new Pegawai;
            $p->nip = $this->pegawai->nip;
            $p->nama = $this->pegawai->nama == null ? null : $this->pegawai->nama;
            $p->jabatan = $this->pegawai->jabatan == null ? null : $this->pegawai->jabatan->nama;
            $p->pangkat = $this->pegawai->pangkat == null ? null : $this->pegawai->pangkat->nama;
            $p->golongan = $this->pegawai->pangkat == null ? null : $this->pegawai->pangkat->golongan;
            $p->tanggal_lahir = $this->pegawai->tanggal_lahir;
            $p->skpd_id = $this->skpd->id;
            $p->is_aktif = $this->pegawai->is_aktif;
            $p->urutan = $this->pegawai->jabatan == null ? null : $this->pegawai->jabatan->kelas_id;
            $p->puskesmas_id = $this->pegawai->jabatan == null ? null : $this->pegawai->jabatan->rs_puskesmas_id;
            $p->sekolah_id = $this->pegawai->jabatan == null ? null : $this->pegawai->jabatan->sekolah_id;
            $p->save();
            Log::info('save');
        } else {
            $check->update([
                'jabatan'       => $this->pegawai->jabatan == null ? null : $this->pegawai->jabatan->nama,
                'skpd_id'       => $this->skpd->id,
                'tanggal_lahir' => $this->pegawai->tanggal_lahir,
                'nama'          => $this->pegawai->nama,
                'pangkat'       => $this->pegawai->pangkat == null ? null : $this->pegawai->pangkat->nama,
                'golongan'      => $this->pegawai->pangkat == null ? null : $this->pegawai->pangkat->golongan,
                'is_aktif'      => $this->pegawai->is_aktif,
                'urutan'        => $this->pegawai->jabatan == null ? null : $this->pegawai->jabatan->kelas_id,
                'puskesmas_id'  => $this->pegawai->jabatan == null ? null : $this->pegawai->jabatan->rs_puskesmas_id,
                'sekolah_id'    => $this->pegawai->jabatan == null ? null : $this->pegawai->jabatan->sekolah_id,
            ]);
            Log::info('save');
        }

        // $rekap = Ringkasan::where('nip', $this->pegawai->nip)->get();
        // foreach ($rekap as $item) {
        //     $item->update([
        //         'sekolah_id' => $this->pegawai->jabatan == null ? null : $this->pegawai->jabatan->sekolah_id,
        //     ]);
        // }
    }
}
