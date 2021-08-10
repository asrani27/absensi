<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\Skpd;
use App\Models\Lokasi;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class SkpdController extends Controller
{
    public function index()
    {
        $data = Skpd::get();
        return view('superadmin.skpd.index',compact('data'));
    }

    public function resetpass($id)
    {
        Skpd::find($id)->update([
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
        return view('superadmin.skpd.detail',compact('skpd','page','countPegawai','countLokasi','countCuti'));
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
        return view('superadmin.skpd.detail',compact('skpd','page','pegawai','countPegawai','countLokasi','countCuti'));
    }

    public function lokasi($id)
    {
        $skpd    = Skpd::find($id);
        $lokasi = Lokasi::where('skpd_id', $id)->paginate(10);
        $page    = 'lokasi';
        
        $countPegawai = $this->countPegawai($id);
        $countLokasi  = $this->countLokasi($id);
        $countCuti  = $this->countCuti($id);
        return view('superadmin.skpd.detail',compact('skpd','page','lokasi','countPegawai','countLokasi','countCuti'));
    }

    public function cuti($id)
    {
        $skpd    = Skpd::find($id);
        $cuti    = Cuti::where('skpd_id', $id)->paginate(10);
        $page    = 'cuti';
        
        $countPegawai = $this->countPegawai($id);
        $countLokasi  = $this->countLokasi($id);
        $countCuti  = $this->countCuti($id);
        return view('superadmin.skpd.detail',compact('skpd','page','cuti','countPegawai','countLokasi','countCuti'));
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
        return view('superadmin.skpd.detail',compact('skpd','page','countPegawai','countLokasi','countCuti','bulan','tahun'));
    }
}
