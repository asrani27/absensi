<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pegawai;
use App\Models\Presensi;
use App\Models\PresensiApel;
use App\Models\PresensiHariBesar;
use Illuminate\Http\Request;

class ModController extends Controller
{
    public function index()
    {
        return view('mod.home');
    }

    public function searchPegawai(Request $request)
    {
        $search = $request->get('q');

        $pegawai = Pegawai::where(function ($query) use ($search) {
            $query->where('nip', 'like', '%' . $search . '%')
                ->orWhere('nama', 'like', '%' . $search . '%');
        })
            ->select('nip', 'nama')
            ->limit(10)
            ->get();

        $results = [];
        foreach ($pegawai as $item) {
            $results[] = [
                'id' => $item->nip,
                'text' => $item->nip . ' - ' . $item->nama
            ];
        }

        return response()->json($results);
    }

    public function absensi(Request $req)
    {
        if ($req->button == 'apel') {
            $absen = PresensiApel::where('tanggal', $req->tanggal)->where('nip', $req->nip)->first();
            if ($absen == null) {
                $pegawai = Pegawai::where('nip', $req->nip)->first();
                //create new data
                $new = new PresensiApel();
                $new->tanggal   = $req->tanggal;
                $new->nip       = $pegawai->nip;
                $new->nama      = $pegawai->nama;
                $new->jam       = '07:' . rand(55, 59) . ':' . rand(1, 60);
                $new->skpd_id   = $pegawai->skpd_id;
                $new->lokasi_id = null;
                $new->save();
                $req->flash();
                toastr()->success('Berhasil di simpan');
                return back();
            } else {
                //update data
                $update      = $absen;
                $update->jam = '07:' . rand(55, 59) . ':' . rand(1, 60);
                $update->save();
                $req->flash();
                toastr()->success('Berhasil di update');
                return back();
            }
        } elseif ($req->button == 'haribesar') {
            $absen = PresensiHariBesar::where('tanggal', $req->tanggal)->where('nip', $req->nip)->first();
            if ($absen == null) {
                $pegawai = Pegawai::where('nip', $req->nip)->first();
                //create new data
                $new = new PresensiHariBesar();
                $new->tanggal   = $req->tanggal;
                $new->nip       = $pegawai->nip;
                $new->nama      = $pegawai->nama;
                $new->jam       = '07:' . rand(55, 59) . ':' . rand(1, 60);
                $new->skpd_id   = $pegawai->skpd_id;
                $new->lokasi_id = null;
                $new->save();
                $req->flash();
                toastr()->success('Berhasil di simpan');
                return back();
            } else {
                //update data
                $update      = $absen;
                $update->jam = '07:' . rand(55, 59) . ':' . rand(1, 60);
                $update->save();
                $req->flash();
                toastr()->success('Berhasil di update');
                return back();
            }
        } else {

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
}
