<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Lokasi;
use App\Models\Pegawai;
use App\Models\Puskesmas;
use App\Models\Presensi;
use App\Models\LiburNasional;
use App\Models\Ramadhan;
use App\Models\Jam;
use App\Models\Jam6;
use App\Models\JamRamadhan;
use App\Models\Jam6Ramadhan;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Carbon\CarbonPeriod;
use App\Jobs\SyncUrut;

class PPPKController extends Controller
{

    public function index()
    {
        $data = Pegawai::where('skpd_id', $this->skpd()->id)->where('status_asn', 'PPPK')->orderBy('urutan', 'DESC')->paginate(10);
        $puskesmas = Puskesmas::get();
        return view('admin.pppk.index', compact('data', 'puskesmas'));
    }

    public function create()
    {
        $lokasi = Lokasi::where('skpd_id', $this->skpd()->id)->get();
        return view('admin.pppk.create', compact('lokasi'));
    }

    public function edit($id)
    {
        $data = Pegawai::find($id);
        return view('admin.pppk.edit', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|unique:pegawai,nip',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'lokasi_id' => 'nullable|exists:lokasi,id',
        ]);

        Pegawai::create([
            'nama' => $request->nama,
            'nip' => $request->nip,
            'pangkat' => $request->pangkat,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'status_asn' => 'PPPK',
            'skpd_id' => $this->skpd()->id,
            'lokasi_id' => $request->lokasi_id,
            'jenis_presensi' => 1, // Default value
            'is_aktif' => 1,
        ]);

        toastr()->success('Data PPPK Berhasil Ditambahkan');
        return redirect('/admin/pppk');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|unique:pegawai,nip,' . $id,
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        $pegawai = Pegawai::find($id);
        $pegawai->update([
            'nama' => $request->nama,
            'nip' => $request->nip,
            'pangkat' => $request->pangkat,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        toastr()->success('Data PPPK Berhasil Diupdate');
        return redirect('/admin/pppk');
    }

    public function skpd()
    {
        return Auth::user()->skpd;
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

        $puskesmas = Puskesmas::get();
        return view('admin.pegawai.index', compact('data', 'puskesmas'))->withInput(request()->all());
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

    public function createUserSingle($id)
    {
        $pegawai = Pegawai::find($id);
        
        if ($pegawai->user_id != null) {
            toastr()->error('User sudah ada untuk pegawai ini');
            return back();
        }

        $rolePegawai = Role::where('name', 'pegawai')->first();
        
        DB::beginTransaction();
        try {
            $check = User::where('username', $pegawai->nip)->first();
            if ($check == null) {
                $user = new User;
                $user->name = $pegawai->nama;
                $user->username = $pegawai->nip;
                $user->password = bcrypt('pppk');
                $user->save();

                $pegawai->update([
                    'user_id' => $user->id,
                ]);

                //Create Role
                $user->roles()->attach($rolePegawai);
            } else {
                $pegawai->update([
                    'user_id' => $check->id,
                ]);
            }
            
            DB::commit();
            toastr()->success('User Berhasil Dibuat. Username: ' . $pegawai->nip . ', Password: pppk');
            return back();
        } catch (\Exception $e) {
            DB::rollback();
            toastr()->error('Create User Gagal: ' . $e->getMessage());
            return back();
        }
    }

    public function resetpass($id)
    {
        if (Auth::user()->skpd != null) {
            $this->authorize('edit', Pegawai::find($id));
        }

        $p = Pegawai::find($id);
        User::where('id', $p->user_id)->first()->update(['password' => bcrypt(Carbon::parse($p->tanggal_lahir)->format('dmY'))]);
        toastr()->success('Password Baru : ' . Carbon::parse($p->tanggal_lahir)->format('dmY'));
        return back();
    }

    public function lokasi($id)
    {
        $data = Pegawai::find($id);
        $lokasi = Lokasi::where('skpd_id', $this->skpd()->id)->get();
        return view('admin.pppk.lokasi', compact('data', 'lokasi'));
    }

    public function editlokasi($id)
    {
        $this->authorize('edit', Pegawai::find($id));

        $data = Pegawai::find($id);
        $lokasi = Lokasi::where('skpd_id', $this->skpd()->id)->get();
        return view('admin.pppk.editlokasi', compact('data', 'lokasi'));
    }

    public function storeLokasi(Request $req, $id)
    {
        $data = Pegawai::find($id)->update([
            'lokasi_id' => $req->lokasi_id,
        ]);
        toastr()->success('Lokasi Presensi Berhasil Di Update');
        return redirect('/admin/pppk');
    }

    public function updateLokasi(Request $req, $id)
    {
        $data = Pegawai::find($id)->update([
            'lokasi_id' => $req->lokasi_id,
        ]);
        toastr()->success('Lokasi Presensi Berhasil Di Update');
        return redirect('/admin/pppk');
    }

    public function presensi($id)
    {
        $this->authorize('edit', Pegawai::find($id));

        $pegawai = Pegawai::find($id);
        $data = null;

        return view('admin.pppk.presensi', compact('pegawai', 'data', 'id'));
    }

    public function tampilkanPresensi($id)
    {
        $bulan = request()->bulan;
        $tahun = request()->tahun;
        $pegawai = Pegawai::find($id);
        $data = Presensi::where('nip', $pegawai->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->orderBy('tanggal', 'ASC')->get();
        request()->flash();

        return view('admin.pppk.presensi', compact('data', 'pegawai'));
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
        $this->authorize('edit', Pegawai::find($id));

        $data = Pegawai::find($id);
        return view('admin.pppk.jenispresensi', compact('data'));
    }

    public function simpanjenispresensi(Request $req, $id)
    {
        Pegawai::find($id)->update([
            'jenis_presensi' => $req->jenis_presensi,
        ]);
        toastr()->success('Jenis Presensi Berhasil Di Update');
        return redirect('/admin/pppk');
    }

    public function pegawaiPuskesmas(Request $req)
    {
        $puskesmas = request()->get('puskesmas_id');

        if ($puskesmas == '34') {
            $data = Pegawai::where('skpd_id', '34')->where('puskesmas_id', null)->orderBy('urutan', 'DESC')->paginate(10);
        } else {
            $data = Pegawai::where('puskesmas_id', $puskesmas)->orderBy('urutan', 'DESC')->paginate(10);
        }
        $data->appends(['puskesmas_id' => $puskesmas])->links();
        $puskesmas = Puskesmas::get();
        $req->flash();
        return view('admin.pppk.index', compact('data', 'puskesmas'));
    }

    public function detailPresensi($id, $bulan, $tahun)
    {
        $pegawai = Pegawai::find($id);
        $data = Presensi::where('nip', $pegawai->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->orderby('tanggal', 'ASC')->get()->map(function ($item) {
            //dd($item);
            $item->liburnasional = LiburNasional::where('tanggal', $item->tanggal)->first() == null ? null : LiburNasional::where('tanggal', $item->tanggal)->first()->deskripsi;
            return $item;
        });
        //dd($data, LiburNasional::where('tanggal', '2023-05-01')->get());
        return view('admin.pppk.detailpresensi', compact('data', 'bulan', 'tahun', 'id', 'pegawai'));
    }

    public function editPresensi($id, $bulan, $tahun, $id_presensi)
    {
        $data = Presensi::find($id_presensi);
        return view('admin.pppk.editpresensi', compact('data', 'id', 'bulan', 'tahun'));
    }

    public function updatePresensi(Request $req, $id, $bulan, $tahun, $id_presensi)
    {

        if (kunciSkpd(Auth::user()->skpd->id, $bulan, $tahun) == 1) {
            toastr()->error('Data Bulan Ini telah di kunci');
            return back();
        }

        $dataawal = Presensi::find($id_presensi);

        if (LiburNasional::where('tanggal', $dataawal->tanggal)->first() != null) {
            Presensi::find($id_presensi)->update([
                'jam_masuk' => $dataawal->tanggal . ' 00:00:00',
                'jam_pulang' => $dataawal->tanggal . ' 00:00:00',
                'terlambat' => 0,
                'lebih_awal' => 0,
            ]);
            toastr()->error('Tanggal Ini termasuk Libur Nasional');
            return redirect('/admin/pegawai/' . $id . '/presensi/' . $bulan . '/' . $tahun);
        }
        Presensi::find($id_presensi)->update([
            'jam_masuk' => $dataawal->tanggal . ' ' . $req->jam_masuk,
            'jam_pulang' => $dataawal->tanggal . ' ' . $req->jam_pulang,
            'jenis_keterangan_id' => null,
        ]);

        $data = Presensi::find($id_presensi);

        $hari = Carbon::parse($data->tanggal)->translatedFormat('l');
        $pegawai = Pegawai::find($id);

        if ($pegawai->jenis_presensi == 1 || $pegawai->jenis_presensi == 4) {
            $ramadhan = Ramadhan::where('tanggal', $data->tanggal)->first();
            if ($ramadhan != null) {
                $jam = JamRamadhan::where('hari', $hari)->first();
            } else {
                $jam = Jam::where('hari', $hari)->first();
            }
        } elseif ($pegawai->jenis_presensi == 2) {
            $ramadhan = Ramadhan::where('tanggal', $data->tanggal)->first();
            if ($ramadhan != null) {
                $jam = Jam6Ramadhan::where('hari', $hari)->first();
            } else {
                $jam = Jam6::where('hari', $hari)->first();
            }
        } else {
        }

        if (Carbon::parse($data->jam_masuk)->format('H:i:s') == '00:00:00' || $data->jam_masuk == null) {
            if (Pegawai::where('nip', $data->nip)->first()->jenis_presensi == 1) {
                if (Carbon::parse($data->tanggal)->translatedFormat('l') == 'Jumat') {
                    $data->update([
                        'terlambat' => 105,
                    ]);
                } else {
                    if (Carbon::parse($data->tanggal)->isWeekend()) {
                        $data->update([
                            'terlambat' => 0,
                        ]);
                    } else {
                        $data->update([
                            'terlambat' => 255,
                        ]);
                    }
                }
            } elseif (Pegawai::where('nip', $data->nip)->first()->jenis_presensi == 2) {
                if (Carbon::parse($data->tanggal)->translatedFormat('l') == 'Jumat') {
                    $data->update([
                        'terlambat' => 105,
                    ]);
                } elseif (Carbon::parse($data->tanggal)->translatedFormat('l') == 'Sabtu') {
                    $data->update([
                        'terlambat' => 180,
                    ]);
                } else {
                    if (Carbon::parse($data->tanggal)->translatedFormat('l') == 'Minggu') {
                        $data->update([
                            'terlambat' => 0,
                        ]);
                    } else {
                        $data->update([
                            'terlambat' => 210,
                        ]);
                    }
                }
            } else {
            }
        } elseif (Carbon::parse($data->jam_masuk)->format('H:i:s') > $jam->jam_masuk) {
            $terlambat = floor(Carbon::parse($data->jam_masuk)->diffInSeconds($data->tanggal . ' ' . $jam->jam_masuk) / 60);
            $data->update([
                'terlambat' => $terlambat,
            ]);
        } else {
            $data->update([
                'terlambat' => 0,
            ]);
        }

        if (Carbon::parse($data->jam_pulang)->format('H:i:s') == '00:00:00' || $data->jam_pulang == null) {
            if (Pegawai::where('nip', $data->nip)->first()->jenis_presensi == 1) {
                if (Carbon::parse($data->tanggal)->translatedFormat('l') == 'Jumat') {
                    $data->update([
                        'lebih_awal' => 105,
                    ]);
                } else {
                    if (Carbon::parse($data->tanggal)->isWeekend()) {
                        $data->update([
                            'lebih_awal' => 0,
                        ]);
                    } else {
                        $data->update([
                            'lebih_awal' => 255,
                        ]);
                    }
                }
            } elseif (Pegawai::where('nip', $data->nip)->first()->jenis_presensi == 2) {
                if (Carbon::parse($data->tanggal)->translatedFormat('l') == 'Jumat') {
                    $data->update([
                        'lebih_awal' => 105,
                    ]);
                } elseif (Carbon::parse($data->tanggal)->translatedFormat('l') == 'Sabtu') {
                    $data->update([
                        'lebih_awal' => 180,
                    ]);
                } else {
                    if (Carbon::parse($data->tanggal)->translatedFormat('l') == 'Minggu') {
                        $data->update([
                            'lebih_awal' => 0,
                        ]);
                    } else {
                        $data->update([
                            'lebih_awal' => 210,
                        ]);
                    }
                }
            } else {
            }
        } elseif (Carbon::parse($data->jam_pulang)->format('H:i:s') < $jam->jam_pulang) {
            $lebih_awal = floor(Carbon::parse($data->jam_pulang)->diffInSeconds($data->tanggal . ' ' . $jam->jam_pulang) / 60);

            $data->update([
                'lebih_awal' => $lebih_awal,
            ]);
        } else {
            $data->update([
                'lebih_awal' => 0,
            ]);
        }

        toastr()->success('Berhasil Di Ubah');
        return redirect('/admin/pppk/' . $id . '/presensi/' . $bulan . '/' . $tahun);
    }

    public function generateTanggal($id, $bulan, $tahun)
    {
        $start = Carbon::createFromFormat('m/Y', $bulan . '/' . $tahun)->startOfMonth()->format('Y-m-d');
        $end = Carbon::createFromFormat('m/Y', $bulan . '/' . $tahun)->endOfMonth()->format('Y-m-d');
        $period = CarbonPeriod::create($start, $end);
        $pegawai = Pegawai::find($id);

        $tanggal = [];
        foreach ($period as $date) {
            //array_push($tanggal, $date->format('Y-m-d'));
            $check = Presensi::where('tanggal', $date->format('Y-m-d'))->where('nip', $pegawai->nip)->first();
            if ($check == null) {
                $attr['nip'] = $pegawai->nip;
                $attr['nama'] = $pegawai->nama;
                $attr['tanggal'] = $date->format('Y-m-d');
                $attr['jam_masuk'] = $date->format('Y-m-d') . ' 00:00:00';
                $attr['jam_pulang'] = $date->format('Y-m-d') . ' 00:00:00';
                $attr['skpd_id'] = $pegawai->skpd_id;
                Presensi::create($attr);
            } else {
            }
        }

        toastr()->success('Presensi Berhasil Di generate');
        return back();
    }
}
