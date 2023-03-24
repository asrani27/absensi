<?php

namespace App\Http\Controllers;

use App\Models\Ramadhan;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class RamadhanController extends Controller
{
    public function index()
    {
        $data = Ramadhan::orderBy('id', 'DESC')->paginate(20);
        return view('superadmin.ramadhan.index', compact('data'));
    }

    public function create()
    {
        return view('superadmin.ramadhan.create');
    }

    public function store(Request $request)
    {
        $period = CarbonPeriod::create($request->tanggal1, $request->tanggal2);
        $dates = [];
        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }
        foreach ($dates as $item) {

            $check = Ramadhan::where('tanggal', $item)->first();
            if ($check == null) {
                Ramadhan::create([
                    'tanggal' => $item,
                ]);
            } else {
            }
        }
        toastr()->success('Sukses Di Simpan');
        return redirect('/superadmin/ramadhan');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data = Ramadhan::find($id);

        return view('superadmin.libur.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {

        $attr = $request->all();

        Ramadhan::find($id)->update($attr);

        toastr()->success('Sukses Di Update');
        return redirect('/superadmin/ramadhan');
    }

    public function destroy($id)
    {
        Ramadhan::find($id)->delete();
        toastr()->success('Sukses Di Hapus');
        return back();
    }
}
