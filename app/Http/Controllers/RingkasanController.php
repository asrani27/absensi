<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RingkasanController extends Controller
{
    public function delete($id)
    {
        Ringkasan::find($id)->delete();
        toastr()->success('Berhasil Di Hapus');
        return back();
    }

    public function masukkanPegawai($bulan, $tahun)
    {
        $skpd_id = Auth::user()->skpd->id;
        $pegawai = Pegawai::where('skpd_id', $skpd_id)->where('puskesmas_id', null)->where('is_aktif', 1)->get();
        foreach ($pegawai as $item) {
            $check = Ringkasan::where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            if ($check == null) {
                $n = new Ringkasan;
                $n->nip = $item->nip;
                $n->nama = $item->nama;
                $n->jabatan = $item->jabatan;
                $n->skpd_id = $skpd_id;
                $n->bulan = $bulan;
                $n->tahun = $tahun;
                $n->save();
            } else {
                $check->update([
                    'jabatan' => $item->jabatan,
                ]);
            }
        }
        toastr()->success('Berhasil Di Masukkan');
        return back();
    }
}
