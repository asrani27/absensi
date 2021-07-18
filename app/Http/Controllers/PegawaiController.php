<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Lokasi;
use GuzzleHttp\Client;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PegawaiController extends Controller
{
    public function index()
    {
        $data = Pegawai::where('skpd_id', $this->skpd()->id)->paginate(10);
        return view('admin.pegawai.index',compact('data'));
    }

    public function skpd()
    {
        return Auth::user()->skpd;
    }

    public function sync()
    {
        $client = new Client(['base_uri' => 'https://tpp.banjarmasinkota.go.id/api/pegawai/skpd/']);
        $response = $client->request('get', $this->skpd()->kode_skpd);
        $data =  json_decode($response->getBody())->data;

        DB::beginTransaction();
        try {
            foreach($data as $item)
            {
                $check = Pegawai::where('nip', $item->nip)->first();
                if($check == null){
                    //simpan data
                    $p = new Pegawai;
                    $p->nip = $item->nip;
                    $p->nama = $item->nama;
                    $p->tanggal_lahir = $item->tanggal_lahir;
                    $p->skpd_id = $this->skpd()->id;
                    $p->is_aktif = $item->is_aktif;
                    $p->save();
                }else{
    
                }
            }
            DB::commit();
            toastr()->success('Sinkronisasi Berhasil');
            return back();
        } catch (\Exception $e) {
            DB::rollback();
            toastr()->error('Sinkronisasi Gagal');
            return back();
        }
    }

    public function createuser()
    {
        $pegawai = Pegawai::where('skpd_id', $this->skpd()->id)->get();
        DB::beginTransaction();
        try {
            foreach($pegawai as $item)
            {
                $check = User::where('username', $item->nip)->first();
                if($check == null){
                    $u = new User;
                    $u->name = $item->nama;
                    $u->username = $item->nip;
                    $u->password = bcrypt($item->tanggal_lahir);
                    $u->save();

                    $user_id = $u->id;

                    $item->update([
                        'user_id' => $user_id,
                    ]);
                }else{
                    $item->update([
                        'user_id' => $check->id,
                    ]);
                }
            }
            DB::commit();
            toastr()->success('User Berhasil Di Buat');
            return back();
        } catch (\Exception $e) {
            DB::rollback();
            toastr()->error('Create User Gagal');
            return back();
        }
    }

    public function resetpass($id)
    {
        $p = Pegawai::find($id);
        User::where('id', $p->user_id)->first()->update(['password' => bcrypt($p->tanggal_lahir)]);
        toastr()->success('Password Baru : '. Carbon::parse($p->tanggal_lahir)->format('dmY'));
        return back();
    }

    public function lokasi($id)
    {
        $data = Pegawai::find($id);
        $lokasi = Lokasi::where('skpd_id', $this->skpd()->id)->get();
        return view('admin.pegawai.lokasi',compact('data','lokasi'));
    }
    
    public function editlokasi($id)
    {
        $data = Pegawai::find($id);
        $lokasi = Lokasi::where('skpd_id', $this->skpd()->id)->get();
        return view('admin.pegawai.editlokasi',compact('data','lokasi'));
    }

    public function storeLokasi(Request $req, $id)
    {
        $data = Pegawai::find($id)->update([
            'lokasi_id' => $req->lokasi_id,
        ]);
        toastr()->success('Lokasi Presensi Berhasil Di Update');
        return redirect('/admin/pegawai');
    }
    
    public function updateLokasi(Request $req, $id)
    {
        $data = Pegawai::find($id)->update([
            'lokasi_id' => $req->lokasi_id,
        ]);
        toastr()->success('Lokasi Presensi Berhasil Di Update');
        return redirect('/admin/pegawai');
    }
}
