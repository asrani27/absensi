<?php

namespace App\Http\Controllers;

use App\Models\Skpd;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function pegawai()
    {
        $agent = new Agent();
        $os = $agent->isSafari();
        dd($os, $agent->device(), $agent->browser(),$agent->isPhone());
        $client = new Client(['base_uri' => 'https://tpp.banjarmasinkota.go.id/api/pegawai/']);
        $response = $client->request('get', Auth::user()->username);
        $data =  json_decode((string) $response->getBody())->data;
        $skpd = Skpd::find($data->skpd_id);
        if(Auth::user()->pegawai->lokasi == null){
            $latlong2 = null;
        }else{
            $lokasi = Auth::user()->pegawai->lokasi;
            $lat        = (float)$skpd->lat;
            $long       = (float)$skpd->long;
            $radius     = (float)$skpd->radius;
            $latlong2 = [
                'lat' => $lat,
                'lng' => $long
            ];
        }
        //dd(Auth::user()->pegawai);
        return view('pegawai.home',compact('skpd','latlong2','os'));
    }

    public function admin()
    {
        $user       = Auth::user()->skpd;
        $lat        = (float)$user->lat;
        $long       = (float)$user->long;
        $radius     = (float)$user->radius;
        $latlong = [
            'lat' => $lat,
            'lng' => $long
        ];
        
        return view('admin.home',compact('latlong','lat','long','radius'));
    }

    public function superadmin()
    {    
        return view('superadmin.home');
    }
}
