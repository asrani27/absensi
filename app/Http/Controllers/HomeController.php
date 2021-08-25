<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cuti;
use App\Models\Skpd;
use App\Models\Lokasi;
use GuzzleHttp\Client;
use App\Models\Rentang;
use App\Models\Presensi;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function pegawai()
    {
        $agent = new Agent();
        $os = $agent->browser();
        
        // $client = new Client(['base_uri' => 'https://tpp.banjarmasinkota.go.id/api/pegawai/']);
        // $response = $client->request('get', Auth::user()->username);
        // $data =  json_decode((string) $response->getBody())->data;
        // $skpd = Skpd::find($data->skpd_id);
        $skpd = '-';
        if(Auth::user()->pegawai->lokasi == null){
            $latlong2 = null;
        }else{
            $lokasi = Auth::user()->pegawai->lokasi;
            $latlong2 = [
                'lat' => $lokasi->lat,
                'lng' => $lokasi->long
            ];
        }
        
        $lokasi = Lokasi::where('skpd_id', Auth::user()->pegawai->skpd_id)->get();

        $today = Carbon::today()->format('Y-m-d');
        $nip   = Auth::user()->pegawai->nip;

        $hari  = Carbon::now()->translatedFormat('l');

        $rentang = Rentang::where('hari', $hari)->first();

        $cuti = Cuti::where('nip', $nip)->where('tanggal_selesai', '>=', $today)->where('tanggal_mulai', '<=', $today)->first();
        
        return view('pegawai.home',compact('skpd','latlong2','os','lokasi','cuti','rentang'));
    }

    public function admin()
    {
        $user       = Auth::user()->skpd;
        
        $today = Carbon::today()->format('Y-m-d');
        $data  = Presensi::where('tanggal', $today)->where('skpd_id',$user->id)->get();

        return view('admin.home',compact('data'));
    }

    public function superadmin()
    {    
        return view('superadmin.home');
    }
}
