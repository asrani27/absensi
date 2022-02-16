<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Jobs\SyncUrut;
use App\Models\Lokasi;
use GuzzleHttp\Client;
use App\Models\Pegawai;
use App\Models\Presensi;
use App\Jobs\SyncPegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PegawaiController extends Controller

{
    public function index()
    {
        $data = Pegawai::where('skpd_id', $this->skpd()->id)->orderBy('urutan', 'DESC')->paginate(10);
        $puskesmas = Puskesmas::get();
        //$data = Pegawai::where('skpd_id', $this->skpd()->id)->paginate(10);
        return view('admin.pegawai.index', compact('data', 'puskesmas'));
    }

    public function skpd()
    {
        return Auth::user()->skpd;
    }

    public function sync()
    {
        $client = new Client(['base_uri' => 'https://tpp.banjarmasinkota.go.id/api/pegawai/skpd/']);
        $response = $client->request('get', $this->skpd()->kode_skpd, ['verify' => false]);
        $data =  json_decode($response->getBody())->data;

        DB::beginTransaction();
        try {
            foreach ($data as $item) {
                SyncPegawai::dispatch($item);
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
            ->where('nama', 'LIKE', '%' . $search . '%')
            ->orWhere(function ($query) use ($search, $skpd_id) {
                $query->where('skpd_id', $skpd_id)->where('nip', 'LIKE', '%' . $search . '%');
            })->paginate(10);
        $data->appends(['search' => $search])->links();
        request()->flash();
        return view('admin.pegawai.index', compact('data'))->withInput(request()->all());
    }

    public function createuser()
    {
        $pegawai = Pegawai::where('skpd_id', $this->skpd()->id)->where('user_id', null)->get()->take(200);

        $rolePegawai = Role::where('name', 'pegawai')->first();
        DB::beginTransaction();
        try {
            foreach ($pegawai as $item) {
                $check = User::where('username', $item->nip)->first();
                if ($check == null) {
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
                } else {
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
        toastr()->success('Password Baru : ' . Carbon::parse($p->tanggal_lahir)->format('dmY'));
        return back();
    }

    public function lokasi($id)
    {
        $data = Pegawai::find($id);
        $lokasi = Lokasi::where('skpd_id', $this->skpd()->id)->get();
        return view('admin.pegawai.lokasi', compact('data', 'lokasi'));
    }

    public function editlokasi($id)
    {
        $data = Pegawai::find($id);
        $lokasi = Lokasi::where('skpd_id', $this->skpd()->id)->get();
        return view('admin.pegawai.editlokasi', compact('data', 'lokasi'));
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

        return view('admin.pegawai.presensi', compact('pegawai', 'data'));
    }

    public function tampilkanPresensi($id)
    {
        $bulan = request()->bulan;
        $tahun = request()->tahun;
        $pegawai = Pegawai::find($id);
        $data = Presensi::where('nip', $pegawai->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->orderBy('tanggal', 'ASC')->get();
        request()->flash();

        return view('admin.pegawai.presensi', compact('data', 'pegawai'));
    }

    public function sortir()
    {
        $client = new Client(['base_uri' => 'https://tpp.banjarmasinkota.go.id/api/']);
        $response = $client->request('get', 'pegawai', ['verify' => false]);
        $data =  json_decode($response->getBody())->data;

        DB::beginTransaction();
        try {
            foreach ($data as $item) {
                SyncUrut::dispatch($item);
            }
            DB::commit();
            toastr()->success('Sinkronisasi Urut Berhasil');
            return back();
        } catch (\Exception $e) {

            DB::rollback();
            toastr()->error('Sinkronisasi Urut Gagal');
            return back();
        }
    }

    public function simpanSortir(Request $req)
    {
        foreach ($req->urutan as $key => $item) {
            if ($item == null) {
            } else {
                Pegawai::find($req->pegawai_id[$key])->update(['urutan' => $item]);
            }
        }
        toastr()->success('Urutan Berhasil Di Update');
        return back();
    }

    public function jenispresensi($id)
    {
        $data = Pegawai::find($id);
        return view('admin.pegawai.jenispresensi', compact('data'));
    }

    public function simpanjenispresensi(Request $req, $id)
    {
        Pegawai::find($id)->update([
            'jenis_presensi' => $req->jenis_presensi,
        ]);
        toastr()->success('Jenis Presensi Berhasil Di Update');
        return redirect('/admin/pegawai');
    }
}
