<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cuti;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Models\JenisKeterangan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CutiController extends Controller
{
    public function index()
    {
        $data = Cuti::orderBy('id', 'DESC')->where('skpd_id', Auth::user()->skpd->id)->paginate(10);
        return view('admin.cuti.index', compact('data'));
    }

    public function create()
    {
        $pegawai = Pegawai::where('skpd_id', Auth::user()->skpd->id)->get();
        $jenis = JenisKeterangan::get();
        return view('admin.cuti.create', compact('pegawai', 'jenis'));
    }

    public function store(Request $request)
    {
        $attr            = $request->all();
        $attr['skpd_id'] = Auth::user()->skpd->id;
        $pegawai         = Pegawai::where('nip', $request->nip)->first();
        $attr['nama']    = $pegawai->nama;
        $attr['puskesmas_id']    = $pegawai->puskesmas_id;

        $today = Carbon::now();

        if ($today->format('m') == Carbon::parse($request->tanggal_mulai)->format('m')) {
            $validator = Validator::make($request->all(), [
                'file' => 'mimes:pdf,png,jpg,jpeg|max:5128'
            ]);

            if ($validator->fails()) {
                toastr()->error('File Harus Berupa pdf/png/jpg/jpeg dan Maks 5MB');
                return back();
            }

            if ($request->hasFile('file')) {
                $filename = $request->file->getClientOriginalName();
                $filename = date('d-m-Y-') . rand(1, 9999) . $filename;
                $request->file->storeAs('/public/cuti', $filename);
                $attr['file'] = $filename;
            } else {
                $attr['file'] = null;
            }

            Cuti::create($attr);

            toastr()->success('Data Di Simpan');
            return redirect('admin/cuti');
        } else {
            // if ($today->diffInDays(Carbon::parse($request->tanggal_mulai)) > 5) {
            //     toastr()->error('Tidak bisa Menambah Data karena data ini telah di rekap pada tanggal 5 setiap bulan');
            //     return back();
            // } else {

            $validator = Validator::make($request->all(), [
                'file' => 'mimes:pdf,png,jpg,jpeg|max:5128'
            ]);

            if ($validator->fails()) {
                toastr()->error('File Harus Berupa pdf/png/jpg/jpeg dan Maks 5MB');
                return back();
            }

            if ($request->hasFile('file')) {
                $filename = $request->file->getClientOriginalName();
                $filename = date('d-m-Y-') . rand(1, 9999) . $filename;
                $request->file->storeAs('/public/cuti', $filename);
                $attr['file'] = $filename;
            } else {
                $attr['file'] = null;
            }

            Cuti::create($attr);

            toastr()->success('Data Di Simpan');
            return redirect('admin/cuti');
            // }
        }
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

    public function upload($id)
    {
        return view('admin.cuti.upload', compact('id'));
    }

    public function storeUpload(Request $req, $id)
    {

        $validator = Validator::make($req->all(), [
            'file' => 'mimes:pdf,png,jpg,jpeg|max:5128'
        ]);

        if ($validator->fails()) {
            toastr()->error('File Harus Berupa pdf/png/jpg/jpeg dan Maks 5MB');
            return back();
        }

        if ($req->hasFile('file')) {
            $filename = $req->file->getClientOriginalName();
            $filename = date('d-m-Y-') . rand(1, 9999) . $filename;
            $req->file->storeAs('/public/cuti', $filename);
            $namafile = $filename;
        } else {
            $namafile = null;
        }

        Cuti::find($id)->update([
            'file' => $namafile
        ]);

        toastr()->success('Berhasil Di upload');
        return redirect('admin/cuti');
    }
    public function destroy($id)
    {
        Cuti::find($id)->delete();
        toastr()->success('Data Di Hapus');
        return back();
    }
}
