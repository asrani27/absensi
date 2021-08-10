<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Jam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JamController extends Controller
{
    public function index()
    {
        $data = Jam::orderBy('id','DESC')->get();
        return view('superadmin.jam.index',compact('data'));
    }
    
    public function create()
    {
        return view('superadmin.jam.create');
    }
    
    public function store(Request $request)
    {
        $check = Jam::where('hari', $request->hari)->first();
        if($check == null){
            $attr = $request->all();
    
            Jam::create($attr);
    
            toastr()->success('Sukses Di Simpan');
            return redirect('/superadmin/jam');
        }else{
            toastr()->error('Hari : '. $request->hari .' Sudah Ada');
            return back();
        }
        
    }
    
    public function show($id)
    {
        //
    }
    
    public function edit($id)
    {
        $data = Jam::find($id);
        
        return view('superadmin.jam.edit',compact('data'));
    }
    
    public function update(Request $request, $id)
    {   
        $attr = $request->all();
        Jam::find($id)->update($attr);
        toastr()->success('Sukses Di Update');
        return redirect('/superadmin/rentang');
    }

    public function destroy($id)
    {
        Jam::find($id)->delete();
        toastr()->success('Sukses Di Hapus');
        return back();
    }
}
