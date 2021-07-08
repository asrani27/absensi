<?php

namespace App\Http\Controllers;

use App\Models\Skpd;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    public function masuk()
    {
        $client = new Client(['base_uri' => 'https://tpp.banjarmasinkota.go.id/api/pegawai/']);
        $response = $client->request('get', Auth::user()->username);
        $data =  json_decode((string) $response->getBody())->data;
        $skpd = Skpd::find($data->skpd_id);
        return view('pegawai.presensi.masuk',compact('skpd'));
    }
    
    public function pulang()
    {
        return view('pegawai.presensi.pulang');
    }
}
