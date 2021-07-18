<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LokasiController extends Controller
{
    public function index()
    {
        $data = Lokasi::orderBy('id','DESC')->get();
        return view('admin.lokasi.index',compact('data'));
    }
    
    public function create()
    {
        return view('admin.lokasi.create');
    }
    
    public function store(Request $request)
    {
        $attr = $request->all();
        $attr['skpd_id'] = Auth::user()->skpd->id;

        Lokasi::create($attr);

        toastr()->success('Sukses Di Simpan');
        return redirect('/admin/lokasi');
        
    }
    
    public function show($id)
    {
        //
    }
    
    public function edit($id)
    {
        $data = Lokasi::find($id);
        $latlong = [
            'lat' => $data->lat,
            'lng' => $data->long
        ];
        return view('admin.lokasi.edit',compact('data','latlong'));
    }
    
    public function update(Request $request, $id)
    {
        
        $attr = $request->all();
        $attr['skpd_id'] = Auth::user()->skpd->id;

        Lokasi::find($id)->update($attr);

        toastr()->success('Sukses Di Update');
        return redirect('/admin/lokasi');
    }

    public function destroy($id)
    {
        
    }
}
