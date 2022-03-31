<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cuti;
use App\Models\Role;
use App\Models\Skpd;
use App\Models\User;
use App\Models\Lokasi;
use App\Models\Pegawai;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SkpdController extends Controller
{
    public function index()
    {
        $data = Skpd::get();
        return view('superadmin.skpd.index', compact('data'));
    }

    public function login($id)
    {
        $user = Skpd::find($id)->user;

        $uuid = Str::random(40);
        if (Auth::loginUsingId($user->id)) {
            Session::put('uuid', $uuid);
            return redirect('/home/admin');
        }
    }

    public function resetpass($id)
    {
        Skpd::find($id)->user->update([
            'password' => bcrypt('adminskpd'),
        ]);
        toastr()->success('password : adminskpd');
        return back();
    }

    public function detail($id)
    {
        $skpd         = Skpd::find($id);
        $page         = null;
        $countPegawai = $this->countPegawai($id);
        $countLokasi  = $this->countLokasi($id);
        $countCuti  = $this->countCuti($id);
        return view('superadmin.skpd.detail', compact('skpd', 'page', 'countPegawai', 'countLokasi', 'countCuti'));
    }

    public function countPegawai($id)
    {
        return Pegawai::where('skpd_id', $id)->get()->count();
    }

    public function countLokasi($id)
    {
        return Lokasi::where('skpd_id', $id)->get()->count();
    }

    public function countCuti($id)
    {
        return Cuti::where('skpd_id', $id)->get()->count();
    }

    public function pegawai($id)
    {
        $skpd     = Skpd::find($id);
        $pegawai  = Pegawai::where('skpd_id', $id)->paginate(10);
        $page     = 'pegawai';

        $countPegawai = $this->countPegawai($id);
        $countLokasi  = $this->countLokasi($id);
        $countCuti  = $this->countCuti($id);
        return view('superadmin.skpd.detail', compact('skpd', 'page', 'pegawai', 'countPegawai', 'countLokasi', 'countCuti', 'id'));
    }

    public function searchPegawai($id)
    {
        $countPegawai = $this->countPegawai($id);
        $countLokasi  = $this->countLokasi($id);
        $countCuti  = $this->countCuti($id);
        $skpd     = Skpd::find($id);
        $page     = 'pegawai';
        $search = request()->search;
        $pegawai  = Pegawai::where('skpd_id', $id)->where('nama', 'like', '%' . $search . '%')->paginate(10);
        return view('superadmin.skpd.detail', compact('skpd', 'page', 'pegawai', 'countPegawai', 'countLokasi', 'countCuti', 'id'));
    }
    public function lokasi($id)
    {
        $skpd    = Skpd::find($id);
        $lokasi = Lokasi::where('skpd_id', $id)->paginate(10);
        $page    = 'lokasi';

        $countPegawai = $this->countPegawai($id);
        $countLokasi  = $this->countLokasi($id);
        $countCuti  = $this->countCuti($id);
        return view('superadmin.skpd.detail', compact('skpd', 'page', 'lokasi', 'countPegawai', 'countLokasi', 'countCuti'));
    }

    public function cuti($id)
    {
        $skpd    = Skpd::find($id);
        $cuti    = Cuti::where('skpd_id', $id)->paginate(10);
        $page    = 'cuti';

        $countPegawai = $this->countPegawai($id);
        $countLokasi  = $this->countLokasi($id);
        $countCuti  = $this->countCuti($id);
        return view('superadmin.skpd.detail', compact('skpd', 'page', 'cuti', 'countPegawai', 'countLokasi', 'countCuti'));
    }

    public function laporan($id)
    {
        $skpd    = Skpd::find($id);
        $page    = 'laporan';

        $countPegawai = $this->countPegawai($id);
        $countLokasi  = $this->countLokasi($id);
        $countCuti    = $this->countCuti($id);
        $bulan = null;
        $tahun = null;
        return view('superadmin.skpd.detail', compact('skpd', 'page', 'countPegawai', 'countLokasi', 'countCuti', 'bulan', 'tahun'));
    }

    public function resetPassPegawai($skpd_id, $pegawai_id)
    {
        $p = Pegawai::find($pegawai_id);
        User::where('id', $p->user_id)->first()->update(['password' => bcrypt(Carbon::parse($p->tanggal_lahir)->format('dmY'))]);
        toastr()->success('Password Baru : ' . Carbon::parse($p->tanggal_lahir)->format('dmY'));
        return back();
    }

    public function buatakun($skpd_id)
    {
        $skpd = Skpd::find($skpd_id);

        $check = User::where('username', $skpd->kode_skpd)->first();
        if ($check != null) {
            //create user
            toastr()->success('Username Sudah ada');
            return back();
        } else {
            $role = Role::where('name', 'admin')->first();

            $n = new User;
            $n->name = $skpd->nama;
            $n->username = $skpd->kode_skpd;
            $n->password = bcrypt('adminskpd');
            $n->save();

            $n->roles()->attach($role);

            $skpd->update([
                'user_id' => $n->id,
            ]);

            toastr()->success('password : adminskpd');
            return back();
        }
    }
}
