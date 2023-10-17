<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pegawai;
use App\Models\Presensi;
use Illuminate\Http\Request;

class ModController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::get();
        return view('mod.home', compact('pegawai'));
    }

    public function absensi(Request $req)
    {
        $absen = Presensi::where('tanggal', $req->tanggal)->where('nip', $req->nip)->first();
        if ($absen == null) {
            $req->flash();
            toastr()->error('Tidak ada Data');
            return back();
        } else {
            if ($req->button == 'masuk') {
                $absen->update([
                    'jam_masuk' => $absen->tanggal . ' 07:' . rand(20, 30) . ':' . rand(1, 60),
                    'terlambat' => 0,
                ]);
                $req->flash();
                toastr()->success('Berhasil di update, Jam Masuk : ' . $absen->jam_masuk);
                return back();
            } else {
                if (Carbon::parse($req->tanggal)->translatedFormat('l') == 'Jumat') {
                    $absen->update([
                        'jam_pulang' => $absen->tanggal . ' 11:' . rand(01, 30) . ':' . rand(1, 60),
                        'lebih_awal' => 0,
                    ]);
                    $req->flash();
                    toastr()->success('Berhasil di update, Jam Masuk : ' . $absen->jam_pulang);
                    return back();
                } else {
                    $absen->update([
                        'jam_pulang' => $absen->tanggal . ' 17:' . rand(01, 30) . ':' . rand(1, 60),
                        'lebih_awal' => 0,
                    ]);
                    $req->flash();
                    toastr()->success('Berhasil di update, Jam Masuk : ' . $absen->jam_pulang);
                    return back();
                }
            }
        }
    }
}
