<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\Pegawai;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanAdminController extends Controller
{
    public function index()
    {
        $bulan = Carbon::today()->format('m');
        $tahun = Carbon::today()->format('Y');
        return view('admin.laporan.index',compact('bulan','tahun'));
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
        if($button == '1'){
            $bulan = request()->bulan;
            $tahun = request()->tahun;
            $pegawai = Presensi::where('skpd_id', $skpd->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->groupBy(function($item){
                $item->nip;
            });
            
            toastr()->error('Dalam Pengembangan');
            return back();
        }else{
            
            $client = new Client(['base_uri' => 'https://tpp.banjarmasinkota.go.id/api/pegawai/']);
            $response = $client->request('get', Auth::user()->username);
            $data =  json_decode((string) $response->getBody())->data;
            dd($data);
            $pegawai = Pegawai::where('skpd_id', $skpd->id)->get();
            dd($pegawai);
        }
    }
}
