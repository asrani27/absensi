<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Lokasi;
use GuzzleHttp\Client;
use App\Models\Pegawai;
use App\Models\Presensi;
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

    public function search()
    {
        $skpd_id = Auth::user()->skpd->id;
        $search = request()->get('search');
        $data   = Pegawai::where('skpd_id', $skpd_id)
        ->where('nama', 'LIKE','%'.$search.'%')
        ->orWhere(function($query)use ($search, $skpd_id){
            $query->where('skpd_id', $skpd_id)->where('nip','LIKE','%'.$search.'%');
        })->paginate(10);
        $data->appends(['search' => $search])->links();
        request()->flash();
        return view('admin.pegawai.index',compact('data'))->withInput(request()->all());
    }

    public function createuser()
    {
        $pegawai = Pegawai::where('skpd_id', $this->skpd()->id)->get();
        $rolePegawai = Role::where('name','pegawai')->first();
        DB::beginTransaction();
        try {
            foreach($pegawai as $item)
            {
                $check = User::where('username', $item->nip)->first();
                if($check == null){
                    $u = new User;
                    $u->name = $item->nama;
                    $u->username = $item->nip;
                    $u->password = bcrypt(Carbon::parse($item->tanggal_lahir)->format('dmY'));
                    $u->save();

                    $user_id = $u->id;

                    $item->update([
                        'user_id' => $user_id,
                    ]);
                    
                    //Create Role
                    $u->roles()->attach($rolePegawai);
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
        User::where('id', $p->user_id)->first()->update(['password' => bcrypt(Carbon::parse($p->tanggal_lahir)->format('dmY'))]);
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

    public function presensi($id)
    {
        $pegawai = Pegawai::find($id);
        $data = null;
        
        return view('admin.pegawai.presensi',compact('pegawai','data'));
    }

    public function tampilkanPresensi($id)
    {
        $bulan = request()->bulan;
        $tahun = request()->tahun;
        $pegawai = Pegawai::find($id);
        $data = Presensi::where('nip', $pegawai->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->orderBy('tanggal','ASC')->get();
        request()->flash();
        
        return view('admin.pegawai.presensi',compact('data','pegawai'));
        
    }
}
