<?php

namespace App\Http\Controllers;

use App\Models\Qr;
use Carbon\Carbon;
use App\Models\Skpd;
use GuzzleHttp\Client;
use App\Models\Presensi;
use Jenssegers\Agent\Agent;
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
                $attr['photo_masuk'] = $req->photo;
                Presensi::create($attr);
                toastr()->success('Berhasil Disimpan');
                return redirect('/pegawai/presensi/masuk');
            }
            else{
                $attr['photo_pulang'] = $req->photo;
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
        
        $agent = new Agent();
        $os = $agent->browser();
        if($os == 'Safari'){
            return view('pegawai.presensi.radius.presensi',compact('skpd','latlong2','jam_masuk','jam_pulang'));
        }else{
            return view('pegawai.presensi.radius.presensi2',compact('skpd','latlong2','jam_masuk','jam_pulang'));
        }
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
        return view('pegawai.presensi.barcode.presensi',compact('skpd','latlong2'));
        //return view('pegawai.presensi.barcode.scan',compact('skpd','latlong2'));
    }

    public function frontCamera()
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
        return view('pegawai.presensi.barcode.front',compact('skpd','latlong2'));
    }
    
    public function backCamera()
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
        return view('pegawai.presensi.barcode.back',compact('skpd','latlong2'));   
    }

    public function manual()
    {
        $client = new Client(['base_uri' => 'https://tpp.banjarmasinkota.go.id/api/pegawai/']);
        $response = $client->request('get', Auth::user()->username);
        $data =  json_decode((string) $response->getBody())->data;
        $skpd = Skpd::find($data->skpd_id);

        $presensi = Presensi::where('tanggal', Carbon::now()->format('Y-m-d'))->where('nip', $this->pegawai()->nip)->first();
        
        return view('pegawai.presensi.manual.presensi',compact('skpd','presensi'));
    }

    public function pegawai()
    {
        return Auth::user()->pegawai;
    }

    public function scanBarcode(Request $req)
    {
        $checkQr = Qr::where('qrcode', $req->qrcode)->where('skpd_id', $this->pegawai()->skpd_id)->first();
        if($checkQr == null){
            toastr()->error('Qrcode Tidak Ada Dalam Database');
            return back();
        }else{    
            if($checkQr->tanggal != Carbon::now()->format('Y-m-d')){
                toastr()->error('Qrcode Ini Telah Kadaluwarsa');
                return back();
            }else{
                $today = Carbon::now()->format('Y-m-d');
                $time  = Carbon::now()->format('H:i:s');
                $check = Presensi::where('tanggal', $today)->where('nip', Auth::user()->username)->first();
                if($check == null){
                    toastr()->error('Tidak Ada Data');
                }else{
                    if($req->jenis == 'masuk'){
                        //presensi masuk
                        if($check->jam_masuk != null){
                            toastr()->error('Anda Sudah melakukan presensi masuk');
                        }else{
                            toastr()->success('Presensi Berhasil Di Simpan');
                            $check->update(['jam_masuk' => $time]);
                        }
                    }else{
                        //presensi pulang
                        toastr()->success('Presensi Pulang Berhasil Di Simpan');
                        $check->update(['jam_pulang' => $time]);
                    }
                }
                return back();
            }
        }
    }

    public function storeManual(Request $req)
    {
        $check = Presensi::where('nip', Auth::user()->username)->where('tanggal', Carbon::today()->format('Y-m-d'))->first();
        if($check == null){
            $attr['nip'] = Auth::user()->username;
            $attr['tanggal'] = Carbon::today()->format('Y-m-d');
            $attr['keterangan'] = $req->keterangan;

            Presensi::create($attr);
            toastr()->success('Berhasil Di Kirim Ke Admin');
        }else{
            if($check->jam_masuk == null){
                $check->update([
                    'keterangan' => $req->keterangan,
                ]);
                toastr()->success('Berhasil Di Kirim Ke Admin');
            }else{
                toastr()->error('Anda Sudah Mengirim data pada tanggal ini');
            }
        }
        return back();
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
                      if($check->jam_masuk == null){
                        if($req->photo == null){
                            toastr()->error('Take Photo Terlebih Dahulu');
                        }else{
                            $check->update([
                                'jam_masuk' => $jam_masuk,
                                'photo_masuk' => $req->photo,
                            ]);
                            toastr()->success('Presensi Masuk Berhasil Disimpan');
                        }
                        return back();
                      }else{
                        toastr()->info('Anda Sudah Melakukan Presensi Masuk');
                        return back();
                      }
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
                    if($req->photo == null){
                        toastr()->error('Take Photo Terlebih Dahulu');
                    }else{
                        $check->update([
                            'jam_pulang' => $jam_pulang,
                            'photo_pulang' => $req->photo,
                        ]);
                        toastr()->success('Presensi Pulang Berhasil DiUpdate');
                    }
                    return back();
                }
            }
        }
    }

    public function testing()
    {
        return view('pegawai.presensi.testing');
    }

    public function savephoto(Request $req)
    {
            $image = $req->image;  // your base64 encoded
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
              
              // save to server (beware of permissions // set ke 775 atau 777)
              $namafoto = Carbon::now()->format('Ymd-His').".png";
              $result = file_put_contents('storage/'.$namafoto, base64_decode($image));
          return back();
          
    }
}
