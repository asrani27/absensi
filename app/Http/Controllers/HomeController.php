<?php

namespace App\Http\Controllers;

use App\Models\Skpd;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function pegawai()
    {
        $client = new Client(['base_uri' => 'https://tpp.banjarmasinkota.go.id/api/pegawai/']);
        $response = $client->request('get', Auth::user()->username);
        $data =  json_decode((string) $response->getBody())->data;
        $skpd = Skpd::find($data->skpd_id);
        return view('pegawai.home',compact('skpd'));
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
