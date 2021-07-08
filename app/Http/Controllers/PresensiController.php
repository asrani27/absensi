<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Skpd;
use GuzzleHttp\Client;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{

    public function skpd()
    {
        $client = new Client(['base_uri' => 'https://tpp.banjarmasinkota.go.id/api/pegawai/']);
        $response = $client->request('get', Auth::user()->username);
        return  json_decode((string) $response->getBody())->data;
    }
    public function masuk()
    {
        $skpd = Skpd::find($this->skpd()->skpd_id);
        $lat        = (float)$skpd->lat;
        $long       = (float)$skpd->long;
        $radius     = (float)$skpd->radius;
        $latlong2 = [
            'lat' => $lat,
            'lng' => $long
        ];
        return view('pegawai.presensi.masuk',compact('skpd','latlong2'));
    }
    
    public function pulang()
    {
        return view('pegawai.presensi.pulang');
    }

    public function storeMasuk(Request $req)
    {
        $datetime = Carbon::now()->format('Y-m-d H:i:s');
        $attr['jam_masuk'] = $datetime;
        $attr['nip'] = Auth::user()->username;
        $attr['skpd_id'] = $this->skpd()->skpd_id;
        $radius = Skpd::find($this->skpd()->skpd_id)->radius;
        
        if((float)$req->jarak * 1000 > $radius){
            toastr()->error('Maaf Anda Di luar jangkauan radius presensi');
            return back();
        }else{
            $check = Presensi::where('nip', $attr['nip'])->where('jam_masuk', 'like', '%'.Carbon::today()->format('Y-m-d').'%')->first();
            
            if($check == null)
            {
                Presensi::create($attr);
                toastr()->success('Berhasil Disimpan');
                return redirect('/pegawai/presensi/masuk');
            }
            else{
                $check->update($attr);
                toastr()->success('Berhasil Diupdate');
                return redirect('/pegawai/presensi/masuk');
            }
        }
    }
}
