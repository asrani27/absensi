<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pegawai;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        foreach($pegawai as $item)
        {
            $check = Presensi::where('nip', $item->nip)->where('tanggal', Carbon::today()->format('Y-m-d'))->first();
            if($check == null){
                $n = new Presensi;
                $n->nip     = $item->nip;
                $n->tanggal = Carbon::today()->format('Y-m-d');
                $n->skpd_id = $skpd_id;
                $n->save();
            }else{
                $check->update([
                    'nama' => $item->nama
                ]);
            }
        }
        
        toastr()->success('Berhasil Di Generate');
        return back();
    }

}
