<?php

namespace App\Http\Controllers;

use App\Models\Rentang;
use Illuminate\Http\Request;

class RentangController extends Controller
{
    public function index()
    {
        $data = Rentang::orderBy('id','DESC')->get();
        return view('superadmin.rentang.index',compact('data'));
    }
    
    public function edit($id)
    {
        $data = Rentang::find($id);
        
        return view('superadmin.rentang.edit',compact('data'));
    }
    
    public function update(Request $request, $id)
    {
        
        $attr = $request->all();

        Rentang::find($id)->update($attr);

        toastr()->success('Sukses Di Update');
        return redirect('/superadmin/jam');
    }
}
