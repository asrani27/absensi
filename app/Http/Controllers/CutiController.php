<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cuti;
use App\Models\Pegawai;
use App\Models\Presensi;
use Carbon\CarbonPeriod;
use App\Models\DetailCuti;
use Illuminate\Http\Request;
use App\Models\LiburNasional;
use App\Models\JenisKeterangan;
use Illuminate\Support\Facades\DB;
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
        DB::beginTransaction();
        try {

            $attr            = $request->all();
            $attr['skpd_id'] = Auth::user()->skpd->id;
            $pegawai         = Pegawai::where('nip', $request->nip)->first();
            $attr['nama']    = $pegawai->nama;
            $attr['puskesmas_id']    = $pegawai->puskesmas_id;


            $period = CarbonPeriod::create($request->tanggal_mulai, $request->tanggal_selesai);
            if (count($period) > 180) {
                toastr()->error('Cuti tidak bisa lebih dari 180 hari');
                $request->flash();
                return back();
            }

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

                $cuti = Cuti::create($attr);
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

                $cuti = Cuti::create($attr);
            }

            foreach ($period as $date) {
                if ($pegawai->jenis_presensi == 1) {
                    if ($date->translatedFormat('l') == 'Sabtu') {
                    } elseif ($date->translatedFormat('l') == 'Minggu') {
                    } else {
                        if (LiburNasional::where('tanggal', $date->format('Y-m-d'))->first() == null) {
                            //simpan cuti tahun di presensi
                            $n = new DetailCuti;
                            $n->cuti_id             = $cuti->id;
                            $n->nip                 = $request->nip;
                            $n->skpd_id             = $pegawai->skpd_id;
                            $n->tanggal             = $date->format('Y-m-d');
                            $n->jenis_keterangan_id = $request->jenis_keterangan_id;
                            $n->save();
                        } else {
                        }
                    }
                } else {
                    if ($date->translatedFormat('l') == 'Minggu') {
                    } else {
                        if (LiburNasional::where('tanggal', $date->format('Y-m-d'))->first() == null) {
                            //simpan cuti tahun di presensi
                            $n = new DetailCuti;
                            $n->cuti_id             = $cuti->id;
                            $n->nip                 = $request->nip;
                            $n->skpd_id             = $pegawai->skpd_id;
                            $n->tanggal             = $date->format('Y-m-d');
                            $n->jenis_keterangan_id = $request->jenis_keterangan_id;
                            $n->save();
                        } else {
                        }
                    }
                }
            }
            DB::commit();
            toastr()->success('Berhasil Menyimpan Cuti');
            return redirect('/admin/cuti');
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            toastr()->error('Sistem Gagal');
            $request->flash();
            return back();
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
        $this->authorize('upload', Cuti::find($id));
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

        $this->authorize('delete', Cuti::find($id));

        $data = Cuti::find($id);

        $period = CarbonPeriod::create($data->tanggal_mulai, $data->tanggal_selesai);
        foreach ($period as $date) {
            if ($date->translatedFormat('l') == 'Minggu') {
            } else {
                if (LiburNasional::where('tanggal', $date->format('Y-m-d'))->first() == null) {
                    //simpan cuti tahun di presensi
                    $check = Presensi::where('nip', $data->nip)->where('tanggal', $date->format('Y-m-d'))->first();
                    if ($check == null) {
                        //save
                        $p = new Presensi;
                        $p->nip = $data->nip;
                        $p->nama = $data->nama;
                        $p->skpd_id = $data->skpd_id;
                        $p->tanggal = $date->format('Y-m-d');
                        $p->jam_masuk = '00:00:00';
                        $p->jam_pulang = '00:00:00';
                        $p->terlambat = 0;
                        $p->lebih_awal = 0;
                        $p->jenis_keterangan_id = null;
                        $p->save();
                    } else {
                        $check->update([
                            'jam_masuk' => '00:00:00',
                            'jam_pulang' => '00:00:00',
                            'terlambat' => 0,
                            'lebih_awal' => 0,
                            'jenis_keterangan_id' => null,
                        ]);
                    }
                } else {
                }
            }
        }
        Cuti::find($id)->delete();
        toastr()->success('Data Di Hapus');
        return back();
    }

    public function search()
    {
        $skpd_id = Auth::user()->skpd->id;
        $search = request()->get('search');
        $data   = Cuti::where('skpd_id', $skpd_id)
            ->where('nama', 'LIKE', '%' . $search . '%')
            ->orWhere(function ($query) use ($search, $skpd_id) {
                $query->where('skpd_id', $skpd_id)->where('nip', 'LIKE', '%' . $search . '%');
            })->paginate(10);
        $data->appends(['search' => $search])->links();
        request()->flash();
        return view('admin.cuti.index', compact('data'));
    }
}
