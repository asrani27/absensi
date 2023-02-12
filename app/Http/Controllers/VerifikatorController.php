<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifikatorController extends Controller
{
    public function verifikator()
    {
        $data = Auth::user()->skpd;
        $pegawai = Pegawai::where('skpd_id', $data->id)->get();
        return view('admin.pegawai.verifikator', compact('data', 'pegawai'));
    }
    public function update(Request $req)
    {
        $data = Auth::user()->skpd;
        $data->kadis = $req->verifikator;
        $data->save();
        toastr()->success('Berhasil Di Update');
        return back();
    }
}
