<?php

namespace App\Http\Controllers;

use App\Models\Skpd;
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
        $skpd = Skpd::find($id);
        $page = null;
        $countPegawai = $this->countPegawai($id);
        return view('superadmin.skpd.detail',compact('skpd','page','countPegawai'));
    }
    
    public function countPegawai($id)
    {
        return Pegawai::where('skpd_id', $id)->get()->count();
    }

    public function pegawai($id)
    {
        $skpd = Skpd::find($id);
        $pegawai = Pegawai::where('skpd_id', $id)->paginate(10);
        $page = 'pegawai';
        
        $countPegawai = $this->countPegawai($id);
        return view('superadmin.skpd.detail',compact('skpd','page','pegawai','countPegawai'));
    }
}
