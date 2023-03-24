<?php

namespace App\Http\Controllers;

use App\Models\Jam6Ramadhan;
use App\Models\JamRamadhan;
use Illuminate\Http\Request;

class JamRamadhanController extends Controller
{

    public function index()
    {
        $jam5 = JamRamadhan::orderBy('id', 'DESC')->get();
        $jam6 = Jam6Ramadhan::orderBy('id', 'DESC')->get();
        return view('superadmin.jamramadhan.index', compact('jam5', 'jam6'));
    }

    public function create()
    {
        return view('superadmin.jam.create');
    }

    public function show($id)
    {
        //
    }

    public function edit5($id)
    {
        $data = JamRamadhan::find($id);
        return view('superadmin.jamramadhan.edit5', compact('data'));
    }

    public function edit6($id)
    {
        $data = Jam6Ramadhan::find($id);
        return view('superadmin.jamramadhan.edit6', compact('data'));
    }

    public function update5(Request $request, $id)
    {
        $attr = $request->all();
        JamRamadhan::find($id)->update($attr);
        toastr()->success('Sukses Di Update');
        return redirect('/superadmin/jamramadhan');
    }

    public function update6(Request $request, $id)
    {
        $attr = $request->all();
        Jam6Ramadhan::find($id)->update($attr);
        toastr()->success('Sukses Di Update');
        return redirect('/superadmin/jamramadhan');
    }
}
