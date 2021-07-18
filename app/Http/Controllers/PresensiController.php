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
    
    public function radius()
    {
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

        $check = Presensi::where('nip', $this->pegawai()->nip)->where('tanggal', Carbon::today()->format('Y-m-d'))->first();
        if($check == null){
            $jam_masuk = '00:00:00';
            $jam_pulang = '00:00:00';
        }else{
            $jam_masuk = $check->jam_masuk;
            $jam_pulang = $check->jam_pulang;
        }
        return view('pegawai.presensi.radius.presensi',compact('skpd','latlong2','jam_masuk','jam_pulang'));
    }

    public function barcode()
    {
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
        //return view('pegawai.presensi.barcode.presensi',compact('skpd','latlong2'));
        return view('pegawai.presensi.barcode.scan',compact('skpd','latlong2'));
    }

    public function pegawai()
    {
        return Auth::user()->pegawai;
    }

    public function storeRadius(Request $req)
    {
        $radius = $this->pegawai()->lokasi->radius;
        if((int)$req->datajarak > (int)$radius){
            toastr()->error('Anda Berada Di Luar Jangkauan Lokasi Presensi');
            return back();
        }else{
            if($req->button == 'masuk'){    
                //Presensi Masuk
                $date      = Carbon::now();
                $tanggal   = $date->format('Y-m-d');
                $jam_masuk = $date->format('H:i:s');

                $check = Presensi::where('nip', $this->pegawai()->nip)->where('tanggal', $tanggal)->first();
                if($check == null){
                      $attr['nip'] = $this->pegawai()->nip;
                      $attr['tanggal'] = $tanggal;
                      $attr['jam_masuk'] = $jam_masuk;
                      Presensi::create($attr);
                      toastr()->success('Presensi Masuk Berhasil Disimpan');
                      return back();
                }else{
                    //Update Data
                      toastr()->info('Anda Sudah Melakukan Presensi Masuk');
                      return back();
                }
            }else{
                //Presensi Pulang
                $date      = Carbon::now();
                $tanggal   = $date->format('Y-m-d');
                $jam_pulang= $date->format('H:i:s');

                $check = Presensi::where('nip', $this->pegawai()->nip)->where('tanggal', $tanggal)->first();
                if($check == null){
                      $attr['nip'] = $this->pegawai()->nip;
                      $attr['tanggal'] = $tanggal;
                      $attr['jam_pulang'] = $jam_pulang;
                      Presensi::create($attr);
                      toastr()->success('Presensi Pulang Berhasil Disimpan');
                      return back();
                }else{
                    //Update Data
                    $check->update([
                        'jam_pulang' => $jam_pulang,
                    ]);
                    toastr()->success('Presensi Pulang Berhasil DiUpdate');
                    return back();
                }
            }
        }
    }
}
