<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\Pegawai;
use App\Models\Presensi;
use App\Models\Ringkasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanAdminController extends Controller
{
    public function index()
    {
        $bulan = Carbon::today()->format('m');
        $tahun = Carbon::today()->format('Y');

        $data = Ringkasan::where('bulan', $bulan)->where('tahun', $tahun)->where('skpd_id', Auth::user()->skpd->id)->get();
        return view('admin.laporan.index',compact('bulan','tahun','data'));
    }

    public function tanggal()
    {
        $tanggal = request()->tanggal;
        $skpd = Auth::user()->skpd;

        $data = Presensi::where('skpd_id', $skpd->id)->where('tanggal', $tanggal)->get();
        return view('admin.laporan.tanggal',compact('data','skpd','tanggal'));
    }
    
    public function bulan()
    {
        $button = request()->button; 
        $skpd = Auth::user()->skpd;
        $bulan   = request()->bulan;
        $tahun   = request()->tahun;
        if($button == '1'){
            $pegawai = Presensi::where('skpd_id', $skpd->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->groupBy(function($item){
                $item->nip;
            });
            
            request()->flash();
            toastr()->error('Dalam Pengembangan');
            return back();
        }else{
            
            $pegawai = Pegawai::where('skpd_id', $skpd->id)->get();
            foreach($pegawai as $item)
            {
                $check = Ringkasan::where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
                if($check == null){
                    $r = new Ringkasan;
                    $r->nip     = $item->nip;
                    $r->nama    = $item->nama;
                    $r->jabatan = $item->jabatan;
                    $r->skpd_id = $item->skpd_id;
                    $r->bulan   = $bulan;
                    $r->tahun   = $tahun;
                    $r->save();
                }else{

                }
            }
            
            $data = Ringkasan::where('bulan', $bulan)->where('tahun', $tahun)->get();
            request()->flash();
            return view('admin.laporan.index',compact('bulan','tahun','data'));
        }
    }
}
