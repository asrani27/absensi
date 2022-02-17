<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pegawai;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function updatelocation(Request $req)
    {
        Auth::user()->skpd->update([
            'lat' => $req->lat,
            'long' => $req->long,
            'radius' => $req->radius,
        ]);

        return back();
    }

    public function generate()
    {
        $skpd_id = Auth::user()->skpd->id;
        $pegawai = Pegawai::where('skpd_id', $skpd_id)->get();

        foreach ($pegawai as $item) {
            $check = Presensi::where('nip', $item->nip)->where('tanggal', Carbon::today()->format('Y-m-d'))->first();
            if ($check == null) {
                $n = new Presensi;
                $n->nip     = $item->nip;
                $n->tanggal = Carbon::today()->format('Y-m-d');
                $n->skpd_id = $skpd_id;
                $n->save();
            } else {
                $check->update([
                    'nama' => $item->nama
                ]);
            }
        }

        toastr()->success('Berhasil Di Generate');
        return back();
    }

    public function tampilgenerate()
    {
        $button = request()->button;
        $tanggal = request()->tanggal;
        if ($button == '1') {

            $check = Presensi::where('tanggal', $tanggal)->where('skpd_id', Auth::user()->skpd->id)->get();
            $data = Presensi::where('skpd_id', Auth::user()->skpd->id)->where('tanggal', $tanggal)->where('puskesmas_id', null)->get()->map(function ($item) use ($check) {
                $item->hapus = $check->where('nip', $item->nip)->count();
                $item->urut = Pegawai::where('nip', $item->nip)->first()->urutan;
                return $item;
            })->sortByDesc('urut');
            request()->flash();

            return view('admin.home', compact('data'));
        } else {
            $skpd_id = Auth::user()->skpd->id;
            $pegawai = Pegawai::where('skpd_id', $skpd_id)->where('is_aktif', 1)->get();

            foreach ($pegawai as $item) {
                $check = Presensi::where('nip', $item->nip)->where('tanggal', $tanggal)->first();
                if ($check == null) {
                    $n = new Presensi;
                    $n->nip     = $item->nip;
                    $n->nama    = $item->nama;
                    $n->tanggal = $tanggal;
                    $n->skpd_id = $skpd_id;
                    $n->save();
                } else {
                    $check->update([
                        'nama' => $item->nama,
                        'skpd_id' => $skpd_id
                    ]);
                }
            }
            request()->flash();
            toastr()->success('Berhasil Di Generate');
            return redirect('/home/admin');
        }
    }

    public function editPresensi($id)
    {
        $data = Presensi::find($id);
        $today = Carbon::now();
        $today->diff($data->tanggal);
        if ($today->format('m') == Carbon::parse($data->tanggal)->format('m')) {
            return view('admin.presensi.edit', compact('data'));
        } else {
            // if ($today->diffInDays(Carbon::parse($data->tanggal)) > 5) {
            //     toastr()->error('Tidak bisa di edit karena data ini telah di rekap pada tanggal 5 setiap bulan');
            //     return back();
            // } else {
            return view('admin.presensi.edit', compact('data'));
            // }
        }
    }

    public function deletePresensi($id)
    {
        Presensi::find($id)->delete();
        toastr()->success('Berhasil Di Hapus');
        return back();
    }
    public function updatePresensi(Request $req, $id)
    {
        if ($req->jam_masuk == '00:00') {
            $jam_masuk = null;
        } else {
            $jam_masuk = $req->jam_masuk;
        }

        if ($req->jam_pulang == '00:00') {
            $jam_pulang = null;
        } else {
            $jam_pulang = $req->jam_pulang;
        }


        Presensi::find($id)->update([
            'jam_masuk' => $jam_masuk,
            'jam_pulang' => $jam_pulang,
            'keterangan' => $req->keterangan
        ]);
        toastr()->success('Presensi Berhasil Di Update');
        return back();
    }

    public function gantipassword()
    {
        return view('admin.gantipass');
    }

    public function updatepassword(Request $req)
    {
        $passlama = Auth::user()->password;
        if (Hash::check($req->passlama, $passlama)) {
            Auth::user()->update([
                'password' => bcrypt($req->passbaru),
            ]);
            toastr()->success('Password Berhasil Di Ubah');
        } else {
            toastr()->error('Password Lama Tidak Cocok');
        }
        return back();
    }
}
