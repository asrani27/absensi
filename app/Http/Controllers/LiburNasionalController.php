<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LiburNasional;

class LiburNasionalController extends Controller
{
    public function index()
    {
        $data = LiburNasional::orderBy('id','DESC')->get();
        return view('superadmin.libur.index',compact('data'));
    }
    
    public function create()
    {
        return view('superadmin.libur.create');
    }
    
    public function store(Request $request)
    {
        $check = LiburNasional::where('tanggal', $request->tanggal)->first();
        if($check == null){
            $attr = $request->all();
    
            LiburNasional::create($attr);
    
            toastr()->success('Sukses Di Simpan');
            return redirect('/superadmin/libur');
        }else{
            toastr()->error('Tanggal ini Sudah Diinput');
            return back();
        }
        
    }
    
    public function show($id)
    {
        //
    }
    
    public function edit($id)
    {
        $data = LiburNasional::find($id);
        
        return view('superadmin.libur.edit',compact('data'));
    }
    
    public function update(Request $request, $id)
    {
        
        $attr = $request->all();

        LiburNasional::find($id)->update($attr);

        toastr()->success('Sukses Di Update');
        return redirect('/superadmin/libur');
    }

    public function destroy($id)
    {
        LiburNasional::find($id)->delete();
        toastr()->success('Sukses Di Hapus');
        return back();
    }
}
