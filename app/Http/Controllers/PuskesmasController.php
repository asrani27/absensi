<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Jam;
use App\Models\Cuti;
use App\Models\Role;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\Presensi;
use App\Models\Puskesmas;
use App\Models\Ringkasan;
use App\Jobs\SyncPuskesmas;
use Illuminate\Http\Request;
use App\Models\JenisKeterangan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade as PDF;

class PuskesmasController extends Controller
{
    public function index()
    {
        $data = Puskesmas::get();
        return view('admin.puskesmas.index', compact('data'));
    }

    public function createuser($id)
    {
        $username = '1.02.01.' . $id;
        $check = User::where('username', $username)->first();
        if ($check == null) {
            $role = Role::where('name', 'puskesmas')->first();
            $n = new User;
            $n->name = Puskesmas::find($id)->nama;
            $n->username = $username;
            $n->password = bcrypt('admin495');
            $n->save();

            $n->roles()->attach($role);

            Puskesmas::find($id)->update(['user_id' => $n->id]);

            toastr()->success('Berhasil Di buat, password : admin495');
            return back();
        } else {
            toastr()->success('Username sudah ada');
            return back();
        }
    }

    public function sync()
    {
        $pegawai = Pegawai::where('puskesmas_id', '!=', null)->get();
        foreach ($pegawai as $item) {
            SyncPuskesmas::dispatch($item);
        }
        toastr()->success('Sinkronisasi berhasil');
        return back();
    }

    public function resetpass($id)
    {
        Puskesmas::find($id)->user->update(['password' => bcrypt('admin495')]);
        toastr()->success('Berhasil Di reset, password : admin495');
        return back();
    }

    public function pegawai()
    {
        $data = Pegawai::where('puskesmas_id', Auth::user()->puskesmas->id)->orderBy('urutan', 'DESC')->paginate(10);

        return view('puskesmas.pegawai.index', compact('data'));
    }

    public function cuti()
    {
        $data = Cuti::where('puskesmas_id', Auth::user()->puskesmas->id)->paginate(10);
        return view('puskesmas.cuti.index', compact('data'));
    }

    public function deletecuti($id)
    {
        Cuti::find($id)->delete();
        toastr()->success('data Cuti Di hapus');
        return back();
    }

    public function createcuti()
    {
        $pegawai = Pegawai::where('puskesmas_id', Auth::user()->puskesmas->id)->get();
        $jenis = JenisKeterangan::get();
        return view('puskesmas.cuti.create', compact('pegawai', 'jenis'));
    }

    public function storecuti(Request $request)
    {
        $attr            = $request->all();
        $attr['skpd_id'] = 34;
        $pegawai         = Pegawai::where('nip', $request->nip)->first();
        $attr['nama']    = $pegawai->nama;
        $attr['puskesmas_id']    = $pegawai->puskesmas_id;

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
        return redirect('puskesmas/cuti');
    }

    public function gantipass()
    {
        return view('puskesmas.gantipass');
    }

    public function updatepass(Request $req)
    {
        $passlama = Auth::user()->password;
        if (Hash::check($req->passlama, $passlama)) {
            Auth::user()->update([
                'password' => bcrypt($req->passbaru),
            ]);
            toastr()->success('Password Berhasil Di Ubah');
        } else {
            toastr()->error('Password Lama Tidak Cocok');
        }
        return back();
    }

    public function searchpegawai()
    {
        $puskesmas_id = Auth::user()->puskesmas->id;
        $search = request()->get('search');
        $data   = Pegawai::where('puskesmas_id', $puskesmas_id)
            ->where('nama', 'LIKE', '%' . $search . '%')
            ->orWhere(function ($query) use ($search, $puskesmas_id) {
                $query->where('puskesmas_id', $puskesmas_id)->where('nip', 'LIKE', '%' . $search . '%');
            })->paginate(10);
        $data->appends(['search' => $search])->links();
        request()->flash();
        return view('puskesmas.pegawai.index', compact('data'))->withInput(request()->all());
    }

    public function jenispresensi($id)
    {
        $data = Pegawai::find($id);
        return view('puskesmas.pegawai.jenis', compact('data'));
    }

    public function updatejenispresensi(Request $request, $id)
    {
        Pegawai::find($id)->update([
            'jenis_presensi' => $request->jenis_presensi
        ]);
        toastr()->success('Berhasil Di Ubah');
        return redirect('/puskesmas/pegawai');
    }

    public function presensi($id)
    {
        $pegawai = Pegawai::find($id);
        $data = null;

        return view('puskesmas.pegawai.presensi', compact('pegawai', 'data', 'id'));
    }

    public function detailPresensi($id, $bulan, $tahun)
    {
        $pegawai = Pegawai::find($id);
        $data = Presensi::where('nip', $pegawai->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();
        return view('puskesmas.pegawai.detailpresensi', compact('data', 'bulan', 'tahun', 'id', 'pegawai'));
    }
    public function editPresensi($id, $bulan, $tahun, $id_presensi)
    {
        $data = Presensi::find($id_presensi);
        return view('puskesmas.pegawai.editpresensi', compact('data', 'id', 'bulan', 'tahun'));
    }

    public function updatePresensi(Request $req, $id, $bulan, $tahun, $id_presensi)
    {
        $hari = Carbon::now()->translatedFormat('l');

        $jam = Jam::where('hari', $hari)->first();

        Presensi::find($id_presensi)->update([
            'jam_masuk' => $req->jam_masuk,
            'jam_pulang' => $req->jam_pulang,
        ]);

        $data = Presensi::find($id_presensi);

        if ($data->jam_masuk == '00:00:00') {
            $data->update([
                'terlambat' => 240,
            ]);
        } elseif ($data->jam_masuk > $jam->jam_masuk) {
            $terlambat = floor(Carbon::parse($data->jam_masuk)->diffInSeconds($jam->jam_masuk) / 60);
            $data->update([
                'terlambat' => $terlambat,
            ]);
        } else {
            $data->update([
                'terlambat' => 0,
            ]);
        }

        if ($data->jam_pulang == '00:00:00') {
            $data->update([
                'lebih_awal' => 240,
            ]);
        } elseif ($data->jam_pulang < $jam->jam_pulang) {
            $lebih_awal = floor(Carbon::parse($data->jam_pulang)->diffInSeconds($jam->jam_pulang) / 60);
            //dd($lebih_awal, $item->jam_pulang, $jam->jam_pulang);
            $data->update([
                'lebih_awal' => $lebih_awal,
            ]);
        } else {
            $data->update([
                'lebih_awal' => 0,
            ]);
        }

        toastr()->success('Berhasil Di Ubah');
        return redirect('/puskesmas/pegawai/' . $id . '/presensi/' . $bulan . '/' . $tahun);
    }

    public function laporan()
    {
        return view('puskesmas.laporan.index');
    }

    public function bulanTahun($bulan, $tahun)
    {
        $data = Ringkasan::where('bulan', $bulan)->where('tahun', $tahun)->where('puskesmas_id', Auth::user()->puskesmas->id)->where('jabatan', '!=', null)->get()
            ->map(function ($item) {
                $item->urut = Pegawai::where('nip', $item->nip)->first()->urutan;
                return $item;
            })->sortByDesc('urut');

        return view('puskesmas.laporan.bulantahun', compact('bulan', 'tahun', 'data'));
    }

    public function bulanPdf($bulan, $tahun)
    {
        $data = Ringkasan::where('bulan', $bulan)->where('tahun', $tahun)->where('puskesmas_id', null)->where('puskesmas_id', Auth::user()->puskesmas->id)->where('jabatan', '!=', null)->get()
            ->map(function ($item) {
                $item->urut = Pegawai::where('nip', $item->nip)->first()->urutan;
                return $item;
            })->sortByDesc('urut');
        $skpd = Auth::user()->skpd;
        $mulai = Carbon::createFromFormat('m/Y', $bulan . '/' . $tahun)->firstOfMonth()->format('d-m-Y');
        $sampai = Carbon::createFromFormat('m/Y', $bulan . '/' . $tahun)->endOfMonth()->format('d-m-Y');

        $pdf = PDF::loadView('puskesmas.laporan.bulanpdf', compact('data', 'skpd', 'mulai', 'sampai'))->setPaper('legal', 'landscape');
        return $pdf->stream();
    }
}
