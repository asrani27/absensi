<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisKeterangan;

class JenisKeteranganController extends Controller
{
    public function index()
    {
        $data = JenisKeterangan::orderBy('id','DESC')->get();
        return view('superadmin.jenis.index',compact('data'));
    }
    
    public function create()
    {
        return view('superadmin.jenis.create');
    }
    
    public function store(Request $request)
    {
        $attr = $request->all();

        JenisKeterangan::create($attr);

        toastr()->success('Sukses Di Simpan');
        return redirect('/superadmin/jenis');        
    }
    
    public function show($id)
    {
        //
    }
    
    public function edit($id)
    {
        $data = JenisKeterangan::find($id);
        
        return view('superadmin.jenis.edit',compact('data'));
    }
    
    public function update(Request $request, $id)
    {
        
        $attr = $request->all();

        JenisKeterangan::find($id)->update($attr);

        toastr()->success('Sukses Di Update');
        return redirect('/superadmin/jenis');
    }

    public function destroy($id)
    {
        JenisKeterangan::find($id)->delete();
        toastr()->success('Sukses Di Hapus');
        return back();
    }
}
