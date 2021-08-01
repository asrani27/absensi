<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Skpd;
use GuzzleHttp\Client;
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
        //dd($os, $agent->device(), $agent->browser(),$agent->isPhone());
        $client = new Client(['base_uri' => 'https://tpp.banjarmasinkota.go.id/api/pegawai/']);
        $response = $client->request('get', Auth::user()->username);
        $data =  json_decode((string) $response->getBody())->data;
        $skpd = Skpd::find($data->skpd_id);
        if(Auth::user()->pegawai->lokasi == null){
            $latlong2 = null;
        }else{
            $lokasi = Auth::user()->pegawai->lokasi;
            // $lat        = (float)$skpd->lat;
            // $long       = (float)$skpd->long;
            // $radius     = (float)$skpd->radius;
            $latlong2 = [
                'lat' => $lokasi->lat,
                'lng' => $lokasi->long
            ];
        }
        
        
        //dd(Auth::user()->pegawai);
        return view('pegawai.home',compact('skpd','latlong2','os'));
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
