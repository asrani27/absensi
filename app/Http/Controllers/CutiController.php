<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\JenisKeterangan;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CutiController extends Controller
{
    public function index()
    {
        $data = Cuti::orderBy('id','DESC')->get();
        return view('admin.cuti.index',compact('data'));
    }

    public function create()
    {
        $pegawai = Pegawai::where('skpd_id', Auth::user()->skpd->id)->get();
        $jenis = JenisKeterangan::get();
        return view('admin.cuti.create',compact('pegawai','jenis'));
    }
    
    public function store(Request $request)
    {
        $attr            = $request->all();
        $attr['skpd_id'] = Auth::user()->skpd->id;
        
        $attr['nama']    = Pegawai::where('nip', $request->nip)->first()->nama;
        
        Cuti::create($attr);

        toastr()->success('Data Di Simpan');
        return redirect('admin/cuti');
    }
    
    public function show($id)
    {
        //
    }
    
    public function edit($id)
    {
        
    }
    
    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {
        Cuti::find($id)->delete();
        toastr()->success('Data Di Hapus');
        return back();
    }
}
