<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LokasiController extends Controller
{
    public function index()
    {
        $data = Lokasi::orderBy('id', 'DESC')->where('skpd_id', Auth::user()->skpd->id)->get();
        return view('admin.lokasi.index', compact('data'));
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
        $this->authorize('edit', Lokasi::find($id));

        $data = Lokasi::find($id);
        $latlong = [
            'lat' => $data->lat,
            'lng' => $data->long
        ];
        return view('admin.lokasi.edit', compact('data', 'latlong'));
    }

    public function update(Request $request, $id)
    {

        $this->authorize('update', Lokasi::find($id));

        $attr = $request->all();
        $attr['skpd_id'] = Auth::user()->skpd->id;

        Lokasi::find($id)->update($attr);

        toastr()->success('Sukses Di Update');
        return redirect('/admin/lokasi');
    }

    public function destroy($id)
    {
        $this->authorize('delete', Lokasi::find($id));

        Lokasi::find($id)->delete();
        toastr()->success('Berhasil Di hapus');
        return back();
    }

    public function lokasiPegawai($id)
    {
        $this->authorize('update', Lokasi::find($id));
        $data = Lokasi::find($id);
        $pegawai = Pegawai::where('skpd_id', Auth::user()->skpd->id)->get();
        return view('admin.lokasi.pegawai', compact('data', 'pegawai', 'id'));
    }

    public function masukkanSemuaPegawai($id)
    {
        $this->authorize('update', Lokasi::find($id));

        $lokasi = Lokasi::find($id);
        $pegawai = Pegawai::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->get();
        foreach ($pegawai as $p) {
            if (!$p->lokasipegawai->contains($id)) {
                $p->lokasipegawai()->attach($lokasi);
            }
        }
        toastr()->success('Berhasil Di Simpan');
        return back();
    }

    public function hapusLokasi($id, $pegawai_id)
    {

        $pegawai = Pegawai::find($pegawai_id);
        $pegawai->lokasiPegawai()->detach($id);
        toastr()->success('Berhasil Di Hapus');
        return back();
    }
}
