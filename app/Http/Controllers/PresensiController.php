<?php

namespace App\Http\Controllers;

use App\Models\Qr;
use Carbon\Carbon;
use App\Models\Skpd;
use GuzzleHttp\Client;
use App\Models\Rentang;
use App\Models\Presensi;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Jobs\PresensiProcessMasuk;
use Illuminate\Support\Facades\DB;
use App\Jobs\PresensiProcessPulang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Alert;

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
        $skpd = "-";
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
                'lat' => $lokasi->lat,
                'lng' => $lokasi->long
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
        
        $hari  = Carbon::now()->translatedFormat('l');

        $rentang = Rentang::where('hari', $hari)->first();
        if($os == 'Safari'){
            return view('pegawai.presensi.radius.presensi',compact('skpd','latlong2','jam_masuk','jam_pulang','os','rentang'));
        }else{
            return view('pegawai.presensi.radius.presensi2',compact('skpd','latlong2','jam_masuk','jam_pulang','rentang'));
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
                'lat' => $lokasi->lat,
                'lng' => $lokasi->long
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
                'lat' => $lokasi->lat,
                'lng' => $lokasi->long
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
                'lat' => $lokasi->lat,
                'lng' => $lokasi->long
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
            if($check->jam_masuk == null || $check->jam_keluar == null){
                
                $validator = Validator::make($req->all(), [
                    'file' => 'mimes:pdf,docx,png,jpg,jpeg|max:8048'
                ]);
                
                if ($validator->fails()) {        
                    toastr()->error('File Harus Berupa pdf/docx/png/jpg/jpeg');    
                    return back();
                }
                
                if($req->hasFile('file'))
                {
                    $filename = $req->file->getClientOriginalName();
                    $filename = date('d-m-Y-').rand(1,9999).$filename;
                                
                    $req->file->storeAs('/public/'.Auth::user()->username.'/presensi/manual',$filename);
                }  

                if($req->file == null){
                    $check->update([
                        'keterangan' => $req->keterangan,
                    ]);
                }else{
                    $check->update([
                        'file' => $filename,
                        'keterangan' => $req->keterangan,
                    ]);
                }
                toastr()->success('Berhasil Di Kirim Ke Admin');
            }else{
                toastr()->error('Anda Sudah Mengirim data pada tanggal ini');
            }
        }
        return back();
    }

    public function checkJam($req)
    {
        $today = Carbon::now();
        $hari  = $today->translatedFormat('l');
        $jam   = $today->format('H:i:s');
        $rentang = Rentang::where('hari', $hari)->first();
        
        if($jam < $rentang->jam_masuk_selesai && $jam > $rentang->jam_masuk_mulai){
            return 'masuk';
        }elseif($jam < $rentang->jam_pulang_selesai && $jam > $rentang->jam_pulang_mulai){
            return 'pulang';
        }else{
            return false;            
        } 
    }

    public function storeRadius(Request $req)
    {
        //Check apakah jam masuk atau jam pulang
        
        if($this->checkJam($req) == 'masuk' && $req->browser == 'Safari'){
            if($this->androidMasuk() == 'simpan'){
                $this->simpanAndroidMasuk($req, 'simpan');
                alert()->success('Presensi Berhasil DiSimpan');
                return redirect('/home/pegawai');
            }elseif($this->androidMasuk() == 'update'){
                $this->simpanAndroidMasuk($req, 'update');
                alert()->success('Presensi Berhasil DiSimpan');
                return redirect('/home/pegawai');
            }else{
                alert()->info('Anda Sudah Melakukan Presensi Masuk');
                return redirect('/home/pegawai');
            }

        }elseif($this->checkJam($req) == 'pulang' && $req->browser == 'Safari'){
            if($this->androidPulang() == 'simpan'){
                $this->simpanAndroidPulang('simpan');
                alert()->success('Presensi Berhasil DiSimpan');
                return redirect('/home/pegawai');
            }else{
                $this->simpanAndroidPulang('update');
                alert()->success('Presensi Berhasil DiSimpan');
                return redirect('/home/pegawai');
            }
            toastr()->success('Presensi Berhasil Disimpan');

        }elseif($this->checkJam($req) == 'masuk' && $req->browser == null) {
            if($this->androidMasuk() == 'simpan'){
                $this->simpanAndroidMasuk($req, 'simpan');
                alert()->success('Presensi Berhasil DiSimpan');
                return redirect('/home/pegawai');
            }elseif($this->androidMasuk() == 'update'){
                $this->simpanAndroidMasuk($req, 'update');
                alert()->success('Presensi Berhasil DiSimpan');
                return redirect('/home/pegawai');
            }else{
                alert()->info('Anda Sudah Melakukan Presensi Masuk');
                return redirect('/home/pegawai');
            }

        }elseif($this->checkJam($req) == 'pulang' && $req->browser == null) {
            if($this->androidPulang() == 'simpan'){
                $this->simpanAndroidPulang('simpan');
                alert()->success('Presensi Berhasil DiSimpan');
                return redirect('/home/pegawai');
            }else{
                $this->simpanAndroidPulang('update');
                alert()->success('Presensi Berhasil DiSimpan');
                return redirect('/home/pegawai');
            }
            toastr()->success('Presensi Berhasil Disimpan');

        }else{
            alert()->error('Di Luar Jam Presensi');
            return redirect('/home/pegawai');
        }     
    }

    public function simpanAndroidMasuk($req, $jenis){
        PresensiProcessMasuk::dispatch($jenis);
    }

    public function simpanAndroidPulang($jenis){
        PresensiProcessPulang::dispatch($jenis);
    }

    public function androidMasuk(){
        $date      = Carbon::now();
        $tanggal   = $date->format('Y-m-d');
        $check = Presensi::where('nip', $this->pegawai()->nip)->where('tanggal', $tanggal)->first();
        if($check == null){
            return 'simpan';
        }else{
            if($check->jam_masuk == null){
                return 'update';
            }else{
                return false;
            }
        }
    }

    public function androidPulang(){
        $date      = Carbon::now();
        $tanggal   = $date->format('Y-m-d');
        $check = Presensi::where('nip', $this->pegawai()->nip)->where('tanggal', $tanggal)->first();
        if($check == null){
            return 'simpan';
        }else{
            return 'update';
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

    public function storeLokasi(Request $req)
    {
        Auth::user()->pegawai->update(['lokasi_id' => $req->lokasi_id]);
        toastr()->success('Lokasi Sukses di Update');
        return back();
    }

    public function gantipassword()
    {
        return view('pegawai.gantipass');
    }
    
    public function updatepassword(Request $req)
    {
        $passlama = Auth::user()->password;
        if(Hash::check($req->passlama , $passlama)){
            Auth::user()->update([
                'password' => bcrypt($req->passbaru),
            ]);
            toastr()->success('Password Berhasil Di Ubah');
        }else{
            toastr()->error('Password Lama Tidak Cocok');
        }
        return back();
    }
}
