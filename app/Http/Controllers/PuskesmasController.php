<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Jam;
use App\Models\Cuti;
use App\Models\Jam6;
use App\Models\Role;
use App\Models\Skpd;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\Presensi;
use Carbon\CarbonPeriod;
use App\Models\Puskesmas;
use App\Models\Ringkasan;
use App\Models\DetailCuti;
use App\Jobs\SyncPuskesmas;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LiburNasional;
use App\Models\JenisKeterangan;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PuskesmasController extends Controller
{
    public function index()
    {
        $data = Puskesmas::get();
        return view('admin.puskesmas.index', compact('data'));
    }

    public function createuser($id)
    {
        $username = '1.02.01.' . $id;
        $check = User::where('username', $username)->first();
        if ($check == null) {
            $role = Role::where('name', 'puskesmas')->first();
            $n = new User;
            $n->name = Puskesmas::find($id)->nama;
            $n->username = $username;
            $n->password = bcrypt('admin495');
            $n->save();

            $n->roles()->attach($role);

            Puskesmas::find($id)->update(['user_id' => $n->id]);

            toastr()->success('Berhasil Di buat, password : admin495');
            return back();
        } else {
            toastr()->success('Username sudah ada');
            return back();
        }
    }

    public function sync()
    {
        $pegawai = Pegawai::where('puskesmas_id', '!=', null)->get();
        foreach ($pegawai as $item) {
            SyncPuskesmas::dispatch($item);
        }
        toastr()->success('Sinkronisasi berhasil');
        return back();
    }

    public function resetpass($id)
    {
        Puskesmas::find($id)->user->update(['password' => bcrypt('admin495')]);
        toastr()->success('Berhasil Di reset, password : admin495');
        return back();
    }

    public function pegawai()
    {
        $data = Pegawai::where('puskesmas_id', Auth::user()->puskesmas->id)->orderBy('urutan', 'DESC')->paginate(10);

        return view('puskesmas.pegawai.index', compact('data'));
    }

    public function cuti()
    {
        $data = Cuti::where('puskesmas_id', Auth::user()->puskesmas->id)->paginate(10);
        return view('puskesmas.cuti.index', compact('data'));
    }

    public function deletecuti($id)
    {
        Cuti::find($id)->delete();
        toastr()->success('data Cuti Di hapus');
        return back();
    }

    public function createcuti()
    {
        $pegawai = Pegawai::where('puskesmas_id', Auth::user()->puskesmas->id)->get();
        $jenis = JenisKeterangan::get();
        return view('puskesmas.cuti.create', compact('pegawai', 'jenis'));
    }

    public function storecuti(Request $request)
    {
        DB::beginTransaction();
        try {

            $attr            = $request->all();
            $attr['skpd_id'] = 34;
            $pegawai         = Pegawai::where('nip', $request->nip)->first();
            $attr['nama']    = $pegawai->nama;
            $attr['puskesmas_id']    = $pegawai->puskesmas_id;

            $period = CarbonPeriod::create($request->tanggal_mulai, $request->tanggal_selesai);
            if (count($period) > 180) {
                toastr()->error('Cuti tidak bisa lebih dari 180 hari');
                $request->flash();
                return back();
            }

            $today = Carbon::now();
            if ($today->format('m') == Carbon::parse($request->tanggal_mulai)->format('m')) {
                $validator = Validator::make($request->all(), [
                    'file' => 'mimes:pdf,png,jpg,jpeg|max:5128'
                ]);

                if ($validator->fails()) {
                    toastr()->error('File Harus Berupa pdf/png/jpg/jpeg dan Maks 5MB');
                    return back();
                }

                if ($request->hasFile('file')) {
                    $filename = $request->file->getClientOriginalName();
                    $filename = date('d-m-Y-') . rand(1, 9999) . $filename;
                    $request->file->storeAs('/public/cuti', $filename);
                    $attr['file'] = $filename;
                } else {
                    $attr['file'] = null;
                }

                $cuti = Cuti::create($attr);
            } else {
                // if ($today->diffInDays(Carbon::parse($request->tanggal_mulai)) > 5) {
                //     toastr()->error('Tidak bisa Menambah Data karena data ini telah di rekap pada tanggal 5 setiap bulan');
                //     return back();
                // } else {

                $validator = Validator::make($request->all(), [
                    'file' => 'mimes:pdf,png,jpg,jpeg|max:5128'
                ]);

                if ($validator->fails()) {
                    toastr()->error('File Harus Berupa pdf/png/jpg/jpeg dan Maks 5MB');
                    return back();
                }

                if ($request->hasFile('file')) {
                    $filename = $request->file->getClientOriginalName();
                    $filename = date('d-m-Y-') . rand(1, 9999) . $filename;
                    $request->file->storeAs('/public/cuti', $filename);
                    $attr['file'] = $filename;
                } else {
                    $attr['file'] = null;
                }

                $cuti = Cuti::create($attr);
            }

            foreach ($period as $date) {
                if ($pegawai->jenis_presensi == 1) {
                    if ($date->translatedFormat('l') == 'Sabtu') {
                    } elseif ($date->translatedFormat('l') == 'Minggu') {
                    } else {
                        if (LiburNasional::where('tanggal', $date->format('Y-m-d'))->first() == null) {
                            //simpan cuti tahun di presensi
                            $n = new DetailCuti;
                            $n->cuti_id             = $cuti->id;
                            $n->nip                 = $request->nip;
                            $n->skpd_id             = $pegawai->skpd_id;
                            $n->tanggal             = $date->format('Y-m-d');
                            $n->jenis_keterangan_id = $request->jenis_keterangan_id;
                            $n->save();
                        } else {
                        }
                    }
                } else {
                    if ($date->translatedFormat('l') == 'Minggu') {
                    } else {
                        if (LiburNasional::where('tanggal', $date->format('Y-m-d'))->first() == null) {
                            //simpan cuti tahun di presensi
                            $n = new DetailCuti;
                            $n->cuti_id             = $cuti->id;
                            $n->nip                 = $request->nip;
                            $n->skpd_id             = $pegawai->skpd_id;
                            $n->tanggal             = $date->format('Y-m-d');
                            $n->jenis_keterangan_id = $request->jenis_keterangan_id;
                            $n->save();
                        } else {
                        }
                    }
                }
            }
            DB::commit();
            toastr()->success('Berhasil Menyimpan Cuti');
            return redirect('puskesmas/cuti');
        } catch (\Exception $e) {
            DB::rollback();
            toastr()->error('Sistem Gagal');
            $request->flash();
            return back();
        }
    }

    public function gantipass()
    {
        return view('puskesmas.gantipass');
    }

    public function updatepass(Request $req)
    {
        $passlama = Auth::user()->password;
        if (Hash::check($req->passlama, $passlama)) {
            Auth::user()->update([
                'password' => bcrypt($req->passbaru),
            ]);
            toastr()->success('Password Berhasil Di Ubah');
        } else {
            toastr()->error('Password Lama Tidak Cocok');
        }
        return back();
    }

    public function searchpegawai()
    {
        $puskesmas_id = Auth::user()->puskesmas->id;
        $search = request()->get('search');
        $data   = Pegawai::where('puskesmas_id', $puskesmas_id)
            ->where('nama', 'LIKE', '%' . $search . '%')
            ->orWhere(function ($query) use ($search, $puskesmas_id) {
                $query->where('puskesmas_id', $puskesmas_id)->where('nip', 'LIKE', '%' . $search . '%');
            })->paginate(10);
        $data->appends(['search' => $search])->links();
        request()->flash();
        return view('puskesmas.pegawai.index', compact('data'))->withInput(request()->all());
    }

    public function jenispresensi($id)
    {
        $data = Pegawai::find($id);
        return view('puskesmas.pegawai.jenis', compact('data'));
    }

    public function updatejenispresensi(Request $request, $id)
    {
        Pegawai::find($id)->update([
            'jenis_presensi' => $request->jenis_presensi
        ]);
        toastr()->success('Berhasil Di Ubah');
        return redirect('/puskesmas/pegawai');
    }

    public function presensi($id)
    {
        $pegawai = Pegawai::find($id);
        $data = null;

        return view('puskesmas.pegawai.presensi', compact('pegawai', 'data', 'id'));
    }

    public function detailPresensi($id, $bulan, $tahun)
    {
        $pegawai = Pegawai::find($id);
        $data = Presensi::where('nip', $pegawai->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->orderby('tanggal', 'ASC')->get()->map(function ($item) {
            //dd($item);
            $item->liburnasional = LiburNasional::where('tanggal', $item->tanggal)->first() == null ? null : LiburNasional::where('tanggal', $item->tanggal)->first()->deskripsi;
            return $item;
        });
        dd($data);
        return view('puskesmas.pegawai.detailpresensi', compact('data', 'bulan', 'tahun', 'id', 'pegawai'));
    }
    public function editPresensi($id, $bulan, $tahun, $id_presensi)
    {
        $data = Presensi::where('id', $id_presensi)->get()->map(function ($item) {
            $item->jam_masuk = Carbon::parse($item->jam_masuk)->format('H:i:s');
            $item->jam_pulang = Carbon::parse($item->jam_pulang)->format('H:i:s');
            return $item;
        })->first();

        return view('puskesmas.pegawai.editpresensi', compact('data', 'id', 'bulan', 'tahun'));
    }

    public function updatePresensi(Request $req, $id, $bulan, $tahun, $id_presensi)
    {

        $dataawal = Presensi::find($id_presensi);

        if (LiburNasional::where('tanggal', $dataawal->tanggal)->first() != null) {
            Presensi::find($id_presensi)->update([
                'jam_masuk' => $dataawal->tanggal . ' 00:00:00',
                'jam_pulang' => $dataawal->tanggal . ' 00:00:00',
                'terlambat' => 0,
                'lebih_awal' => 0,
            ]);
            toastr()->error('Tanggal Ini termasuk Libur Nasional');
            return redirect('/puskesmas/pegawai/' . $id . '/presensi/' . $bulan . '/' . $tahun);
        }

        $tanggalPresensi = Presensi::find($id_presensi);

        $hari = Carbon::parse($tanggalPresensi->tanggal)->translatedFormat('l');

        $jam = Jam6::where('hari', $hari)->first();

        Presensi::find($id_presensi)->update([
            'jam_masuk' => $dataawal->tanggal . ' ' . $req->jam_masuk,
            'jam_pulang' => $dataawal->tanggal . ' ' . $req->jam_pulang,
            'jenis_keterangan_id' => null,
        ]);

        $data = Presensi::find($id_presensi);
        $jm = Carbon::parse($data->jam_masuk)->format('H:i:s');
        $jp = Carbon::parse($data->jam_pulang)->format('H:i:s');
        if ($jm == '00:00:00' && $jp == '00:00:00') {
            //di anggap tidak hadir
            $data->update([
                'terlambat' => 0,
                'lebih_awal' => 0,
            ]);
        } else {
            if ($jm == '00:00:00') {
                if (Carbon::parse($data->tanggal)->translatedFormat('l') == 'Jumat') {
                    $data->update([
                        'terlambat' => 105,
                    ]);
                } elseif (Carbon::parse($data->tanggal)->translatedFormat('l') == 'Sabtu') {
                    $data->update([
                        'terlambat' => 180,
                    ]);
                } else {
                    $data->update([
                        'terlambat' => 210,
                    ]);
                }
            } elseif ($data->jam_masuk > $data->tanggal . ' ' . $jam->jam_masuk) {

                $terlambat = floor(Carbon::parse($data->jam_masuk)->diffInSeconds($data->tanggal . ' ' . $jam->jam_masuk) / 60);
                $data->update([
                    'terlambat' => $terlambat,
                ]);
            } else {
                $data->update([
                    'terlambat' => 0,
                ]);
            }

            if ($jp == '00:00:00') {
                if (Carbon::parse($data->tanggal)->translatedFormat('l') == 'Jumat') {
                    $data->update([
                        'lebih_awal' => 105,
                    ]);
                } elseif (Carbon::parse($data->tanggal)->translatedFormat('l') == 'Sabtu') {
                    $data->update([
                        'lebih_awal' => 180,
                    ]);
                } else {
                    $data->update([
                        'lebih_awal' => 210,
                    ]);
                }
            } elseif ($data->jam_pulang < $data->tanggal . ' ' . $jam->jam_pulang) {

                $lebih_awal = floor(Carbon::parse($data->jam_pulang)->diffInSeconds($data->tanggal . ' ' . $jam->jam_pulang) / 60);

                $data->update([
                    'lebih_awal' => $lebih_awal,
                ]);
            } else {
                $data->update([
                    'lebih_awal' => 0,
                ]);
            }
        }


        toastr()->success('Berhasil Di Ubah');
        return redirect('/puskesmas/pegawai/' . $id . '/presensi/' . $bulan . '/' . $tahun);
    }

    public function laporan()
    {
        return view('puskesmas.laporan.index');
    }

    public function bulanTahun($bulan, $tahun)
    {
        $data = Ringkasan::where('bulan', $bulan)->where('tahun', $tahun)->where('puskesmas_id', Auth::user()->puskesmas->id)->where('jabatan', '!=', null)->get()
            ->map(function ($item) {
                $item->urut = Pegawai::where('nip', $item->nip)->first()->urutan;
                $item->jenis_presensi = Pegawai::where('nip', $item->nip)->first()->jenis_presensi;
                return $item;
            })->where('jenis_presensi', '!=', 3)->sortByDesc('urut');
        return view('puskesmas.laporan.bulantahun', compact('bulan', 'tahun', 'data'));
    }

    public function bulanPdf($bulan, $tahun)
    {
        if (Auth::user()->puskesmas->id == 8) {
            $data = Ringkasan::where('bulan', $bulan)->where('tahun', $tahun)->where('puskesmas_id', Auth::user()->puskesmas->id)->where('jabatan', '!=', null)->get()
                ->map(function ($item) {
                    $item->urut = Pegawai::where('nip', $item->nip)->first()->urutan;
                    $item->jenis_presensi = Pegawai::where('nip', $item->nip)->first()->jenis_presensi;
                    return $item;
                })->where('jenis_presensi', '!=', 3)->sortByDesc('urut');
        } else {
            $data = Ringkasan::where('bulan', $bulan)->where('tahun', $tahun)->where('puskesmas_id', Auth::user()->puskesmas->id)->where('jabatan', '!=', null)->get()
                ->map(function ($item) {
                    $item->urut = Pegawai::where('nip', $item->nip)->first()->urutan;
                    return $item;
                })->sortByDesc('urut');
        }
        $skpd = Auth::user()->puskesmas;
        $mulai = Carbon::createFromFormat('m/Y', $bulan . '/' . $tahun)->firstOfMonth()->format('d-m-Y');
        $sampai = Carbon::createFromFormat('m/Y', $bulan . '/' . $tahun)->endOfMonth()->format('d-m-Y');

        $pdf = PDF::loadView('puskesmas.laporan.bulanpdf', compact('data', 'skpd', 'mulai', 'sampai', 'bulan', 'tahun'))->setPaper('legal', 'landscape');
        return $pdf->stream();
    }

    public function masukkanPegawai($bulan, $tahun)
    {
        $puskesmas_id = Auth::user()->puskesmas->id;
        if ($puskesmas_id == 36 || $puskesmas_id == 37) {
            $pegawai = Pegawai::where('puskesmas_id', $puskesmas_id)->where('is_aktif', 1)->get();
        } else {
            $pegawai = Pegawai::where('puskesmas_id', $puskesmas_id)->where('is_aktif', 1)->where('jenis_presensi', 2)->get();
        }
        //dd(Pegawai::where('puskesmas_id', $puskesmas_id)->get());
        //dd($pegawai);
        foreach ($pegawai as $item) {
            //dd('s');
            $check = Ringkasan::where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            //dd($check);
            if ($check == null) {
                $n = new Ringkasan;
                $n->nip = $item->nip;
                $n->nama = $item->nama;
                $n->jabatan = $item->jabatan;
                $n->skpd_id = $item->skpd_id;
                $n->puskesmas_id = $puskesmas_id;
                $n->jenis_presensi = $item->jenis_presensi;
                $n->bulan = $bulan;
                $n->tahun = $tahun;
                $n->save();
            } else {
                $check->update([
                    'jabatan' => $item->jabatan,
                    'puskesmas_id' => $puskesmas_id,
                    'jenis_presensi' => $item->jenis_presensi,
                ]);
            }
        }
        toastr()->success('Berhasil Di Masukkan');
        return back();
    }
    public function masukkanPegawaiShift($bulan, $tahun)
    {
        $puskesmas_id = Auth::user()->puskesmas->id;
        $pegawai = Pegawai::where('puskesmas_id', $puskesmas_id)->where('is_aktif', 1)->where('jenis_presensi', 3)->get();
        foreach ($pegawai as $item) {
            $check = Ringkasan::where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            if ($check == null) {
                $n = new Ringkasan;
                $n->nip = $item->nip;
                $n->nama = $item->nama;
                $n->jabatan = $item->jabatan;
                $n->skpd_id = $item->skpd_id;
                $n->puskesmas_id = $puskesmas_id;
                $n->jenis_presensi = $item->jenis_presensi;
                $n->bulan = $bulan;
                $n->tahun = $tahun;
                $n->save();
            } else {
                $check->update([
                    'jabatan' => $item->jabatan,
                    'puskesmas_id' => $puskesmas_id,
                    'jenis_presensi' => $item->jenis_presensi,
                ]);
            }
        }
        toastr()->success('Berhasil Di Masukkan');
        return back();
    }
    public function hitungpersen($bulan, $tahun)
    {
        // if (kunciSkpd(Auth::user()->skpd->id, $bulan, $tahun) == 1) {
        //     toastr()->error('Data Bulan Ini telah di kunci dan tidak bisa di ubah');
        //     return back();
        // }


        $ringkasan = Ringkasan::where('puskesmas_id', Auth::user()->puskesmas->id)->where('bulan', $bulan)->where('tahun', $tahun)->get();
        foreach ($ringkasan as $item) {
            $jumlahhari = $item->jumlah_hari;
            $hadir = $item->kerja + $item->sc + $item->tr + $item->d + $item->c;

            //datang lambat
            if ($item->datang_lambat == 0) {
                $kurangi_persen_terlambat = 0;
            } elseif ($item->datang_lambat < 31) {
                $kurangi_persen_terlambat = 0.5;
            } elseif ($item->datang_lambat < 61) {
                $kurangi_persen_terlambat = 1;
            } elseif ($item->datang_lambat < 91) {
                $kurangi_persen_terlambat = 1.25;
            } elseif ($item->datang_lambat >= 91) {
                $kurangi_persen_terlambat = 1.5;
            } else {
                $kurangi_persen_terlambat = 0;
            }

            //pulang cepat
            if ($item->pulang_cepat == 0) {
                $kurangi_persen_pulangcepat = 0;
            } elseif ($item->pulang_cepat < 31) {
                $kurangi_persen_pulangcepat = 0.5;
            } elseif ($item->pulang_cepat < 61) {
                $kurangi_persen_pulangcepat = 1;
            } elseif ($item->pulang_cepat < 91) {
                $kurangi_persen_pulangcepat = 1.25;
            } elseif ($item->pulang_cepat >= 91) {
                $kurangi_persen_pulangcepat = 1.55;
            } else {
                $kurangi_persen_pulangcepat = 0;
            }
            //dd($kurangi_persen_pulangcepat, $kurangi_persen_terlambat);
            try {
                $persen = round((($hadir / $jumlahhari) * 100), 2) - $kurangi_persen_terlambat - $kurangi_persen_pulangcepat;
                if ($persen < 0) {
                    $updatepersen = 0;
                } else {
                    $updatepersen = $persen;
                }
                $item->update(['persen_kehadiran' => $updatepersen]);
            } catch (\Exception $e) {
                $item->update(['persen_kehadiran' => 0]);
            }
        }
        toastr()->success('Persentase Selesai');
        return back();
    }

    public function hitungSemua($bulan, $tahun)
    {

        $ringkasan = Ringkasan::where('puskesmas_id', Auth::user()->puskesmas->id)->where('bulan', $bulan)->where('tahun', $tahun)->get();
        //dd($ringkasan);
        foreach ($ringkasan as $item) {
            //            dd(jumlahHari($bulan, $tahun)['jumlah_hari']);
            if (Pegawai::where('nip', $item->nip)->first()->jenis_presensi == 1) {
                $jml_hari   = jumlahHari($bulan, $tahun)['jumlah_hari'];
                $jml_jam    = jumlahHari($bulan, $tahun)['jumlah_jam'];
                $terlambat  = telat($item->nip, $bulan, $tahun)->sum('terlambat');
                $lebih_awal = telat($item->nip, $bulan, $tahun)->sum('lebih_awal');

                $countSakit = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 3)->get());
                $countSakitKarenaCovid = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 9)->get());
                $countCutiTahun = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 7)->get());
                $countCutiLain = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 8)->get());
                $countTraining = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 4)->get());
                $countTugas = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 5)->get());
                $countIzin = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 6)->get());
                $countAlpa = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 1)->get());

                $item->update([
                    'jumlah_hari' => $jml_hari,
                    'jumlah_jam' => $jml_jam,
                    'datang_lambat' => $terlambat,
                    'pulang_cepat' => $lebih_awal,
                    //'persen_kehadiran' => round(($jml_jam - $terlambat - $lebih_awal) / $jml_jam * 100, 2),
                    's' => $countSakit,
                    'sc' => $countSakitKarenaCovid,
                    'tr' => $countTraining,
                    'd' => $countTugas,
                    'c' => $countCutiTahun,
                    'l' => $countCutiLain,
                    'i' => $countIzin,
                    'a' => $countAlpa,
                    'o' => jumlahHari($bulan, $tahun)['off'],
                ]);
            } elseif (Pegawai::where('nip', $item->nip)->first()->jenis_presensi == 2) {
                $jml_hari   = jumlahHari6($bulan, $tahun)['jumlah_hari'];
                $jml_jam    = jumlahHari6($bulan, $tahun)['jumlah_jam'];
                $terlambat  = telat($item->nip, $bulan, $tahun)->sum('terlambat');
                $lebih_awal = telat($item->nip, $bulan, $tahun)->sum('lebih_awal');

                $countSakit = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 3)->get());
                $countSakitKarenaCovid = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 9)->get());
                $countCutiTahun = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 7)->get());
                $countCutiLain = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 8)->get());
                $countTraining = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 4)->get());
                $countTugas = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 5)->get());
                $countIzin = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 6)->get());
                $countAlpa = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 1)->get());
                //dd($jml_hari);
                $item->update([
                    'jumlah_hari' => $jml_hari,
                    'jumlah_jam' => $jml_jam,
                    'datang_lambat' => $terlambat,
                    'pulang_cepat' => $lebih_awal,
                    //'persen_kehadiran' => round(($jml_jam - $terlambat - $lebih_awal) / $jml_jam * 100, 2),
                    's' => $countSakit,
                    'sc' => $countSakitKarenaCovid,
                    'tr' => $countTraining,
                    'd' => $countTugas,
                    'c' => $countCutiTahun,
                    'l' => $countCutiLain,
                    'i' => $countIzin,
                    'a' => $countAlpa,
                    'o' => jumlahHari6($bulan, $tahun)['off'],
                ]);
            } else {
            }
        }

        toastr()->success('Selesai Di Hitung');
        return back();
    }


    public function hitungSemuaShift($bulan, $tahun)
    {
        $ringkasan = Ringkasan::where('puskesmas_id', Auth::user()->puskesmas->id)->where('jenis_presensi', 3)->where('bulan', $bulan)->where('tahun', $tahun)->get();
        foreach ($ringkasan as $item) {
            $jml_jam    = 150 * 60;
            $terlambat  = 0;
            $lebih_awal = 0;

            $countSakit = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 3)->get());
            $countSakitKarenaCovid = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 9)->get());
            $countCutiTahun = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 7)->get());
            $countCutiLain = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 8)->get());
            $countTraining = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 4)->get());
            $countTugas = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 5)->get());
            $countIzin = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 6)->get());
            $countAlpa = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 1)->get());

            $item->update([
                'jumlah_jam' => $jml_jam,
                'datang_lambat' => $terlambat,
                'pulang_cepat' => $lebih_awal,
                'persen_kehadiran' => 100,
                's' => $countSakit,
                'sc' => $countSakitKarenaCovid,
                'tr' => $countTraining,
                'd' => $countTugas,
                'c' => $countCutiTahun,
                'l' => $countCutiLain,
                'i' => $countIzin,
                'a' => $countAlpa,
                'o' => 0,
            ]);
        }

        toastr()->success('Selesai Di Hitung');
        return back();
    }

    public function hitung($id, $bulan, $tahun)
    {
        $data = Ringkasan::find($id);
        if (Pegawai::where('nip', $data->nip)->first()->jenis_presensi == 1) {
            $jml_hari   = jumlahHari($bulan, $tahun)['jumlah_hari'];
            $jml_jam    = jumlahHari($bulan, $tahun)['jumlah_jam'];
            $terlambat  = telat($data->nip, $bulan, $tahun)->sum('terlambat');
            $lebih_awal = telat($data->nip, $bulan, $tahun)->sum('lebih_awal');
            $countSakit = count(Presensi::where('nip', $data->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 3)->get());
            $countSakitKarenaCovid = count(Presensi::where('nip', $data->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 9)->get());
            $countCutiTahun = count(Presensi::where('nip', $data->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 7)->get());
            $countCutiLain = count(Presensi::where('nip', $data->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 8)->get());
            $countTraining = count(Presensi::where('nip', $data->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 4)->get());
            $countTugas = count(Presensi::where('nip', $data->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 5)->get());
            $countIzin = count(Presensi::where('nip', $data->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 6)->get());
            $countAlpa = count(Presensi::where('nip', $data->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 1)->get());

            $data->update([
                'jumlah_hari' => $jml_hari,
                'jumlah_jam' => $jml_jam,
                'datang_lambat' => $terlambat,
                'pulang_cepat' => $lebih_awal,
                'persen_kehadiran' => round(($jml_jam - $terlambat - $lebih_awal) / $jml_jam * 100, 2),
                's' => $countSakit,
                'sc' => $countSakitKarenaCovid,
                'tr' => $countTraining,
                'd' => $countTugas,
                'c' => $countCutiTahun,
                'l' => $countCutiLain,
                'i' => $countIzin,
                'a' => $countAlpa,
            ]);
            toastr()->success('Berhasil Di Hitung');
            return back();
        } elseif (Pegawai::where('nip', $data->nip)->first()->jenis_presensi == 2) {
            $jml_hari   = jumlahHari6($bulan, $tahun)['jumlah_hari'];
            $jml_jam    = jumlahHari6($bulan, $tahun)['jumlah_jam'];
            $terlambat  = telat($data->nip, $bulan, $tahun)->sum('terlambat');
            $lebih_awal = telat($data->nip, $bulan, $tahun)->sum('lebih_awal');

            $countSakit = count(Presensi::where('nip', $data->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 3)->get());
            $countSakitKarenaCovid = count(Presensi::where('nip', $data->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 9)->get());
            $countCutiTahun = count(Presensi::where('nip', $data->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 7)->get());
            $countCutiLain = count(Presensi::where('nip', $data->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 8)->get());
            $countTraining = count(Presensi::where('nip', $data->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 4)->get());
            $countTugas = count(Presensi::where('nip', $data->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 5)->get());
            $countIzin = count(Presensi::where('nip', $data->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 6)->get());
            $countAlpa = count(Presensi::where('nip', $data->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 1)->get());

            $data->update([
                'jumlah_hari' => $jml_hari,
                'jumlah_jam' => $jml_jam,
                'datang_lambat' => $terlambat,
                'pulang_cepat' => $lebih_awal,
                'persen_kehadiran' => round(($jml_jam - $terlambat - $lebih_awal) / $jml_jam * 100, 2),
                's' => $countSakit,
                'sc' => $countSakitKarenaCovid,
                'tr' => $countTraining,
                'd' => $countTugas,
                'c' => $countCutiTahun,
                'l' => $countCutiLain,
                'i' => $countIzin,
                'a' => $countAlpa,
            ]);
            toastr()->success('Berhasil Di Hitung');
            return back();
        } else {
        }
    }


    public function tambahPegawai(Request $req)
    {
        $checkDataPegawai = Pegawai::where('nip', $req->nip)->first();
        if ($checkDataPegawai == null) {
            toastr()->error('Tidak Ada data Di Absensi');
            return back();
        } else {
            $check = Ringkasan::where('nip', $req->nip)->where('bulan', $req->bulan)->where('tahun', $req->tahun)->where('puskesmas_id', Auth::user()->puskesmas->id)->first();
            //dd($check);
            if ($check == null) {
                $n = new Ringkasan;
                $n->nip = $req->nip;
                $n->nama = $checkDataPegawai->nama;
                $n->jabatan = $req->jabatan;
                $n->skpd_id = $checkDataPegawai->skpd_id;
                $n->puskesmas_id = Auth::user()->puskesmas->id;
                $n->bulan = $req->bulan;
                $n->tahun = $req->tahun;
                $n->save();
                toastr()->success('Berhasil Di Tambahkan');
                return back();
            } else {

                toastr()->error('NIP Sudah ada');
                return back();
            }
        }
    }
    public function deleteRingkasan($id)
    {
        Ringkasan::find($id)->delete();
        toastr()->success('Berhasil Di Hapus');
        return back();
    }

    public function hitungtotalharikerja($bulan, $tahun)
    {
        $ringkasan = Ringkasan::where('puskesmas_id', Auth::user()->puskesmas->id)->where('bulan', $bulan)->where('tahun', $tahun)->get();

        foreach ($ringkasan as $item) {
            $hadir = Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->map(function ($item) {
                if (Carbon::parse($item->jam_masuk)->format('H:i') != '00:00' || Carbon::parse($item->jam_pulang)->format('H:i') != '00:00') {
                    $item->hadir = 1;
                } else {
                    $item->hadir = 0;
                }
                return $item;
            })->where('hadir', 1);


            $masuk = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jam_masuk', '!=', null)->where('jam_masuk', 'NOT LIKE', '%00:00:00%')->get());
            $pulang = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jam_pulang', '!=', null)->where('jam_pulang', 'NOT LIKE', '%00:00:00%')->get());

            // if ($masuk > $pulang) {
            //     $totalharikerja = $masuk;
            // } elseif ($masuk < $pulang) {
            //     $totalharikerja = $pulang;
            // } else {
            //     $totalharikerja = $masuk;
            // }
            $item->update([
                'kerja' => $hadir->count(),
                'masuk' => $masuk,
                'keluar' => $pulang,
            ]);
        }

        toastr()->success('Selesai Di Hitung');
        return back();
    }

    public function bulanTahunShift($bulan, $tahun)
    {
        $data = Ringkasan::where('bulan', $bulan)->where('tahun', $tahun)->where('puskesmas_id', Auth::user()->puskesmas->id)->where('jabatan', '!=', null)->get()
            ->map(function ($item) {
                $item->urut = Pegawai::where('nip', $item->nip)->first()->urutan;
                $item->jenis_presensi = Pegawai::where('nip', $item->nip)->first()->jenis_presensi;
                return $item;
            })->where('jenis_presensi', 3)->sortByDesc('urut');


        return view('puskesmas.laporan.bulantahunshift', compact('bulan', 'tahun', 'data'));
    }

    public function gantipasspuskesmas($id)
    {
        $data = Puskesmas::find($id);
        return view('admin.puskesmas.gantipass', compact('data', 'id'));
    }

    public function updatepasspuskesmas(Request $req, $id)
    {
        if (Str::length($req->password1) < 8) {
            toastr()->error('Password Minimal 8 Karakter');
            return back();
        }
        if ($req->password1 != $req->password2) {
            toastr()->error('Password Tidak Sesuai');
            return back();
        }
        Puskesmas::find($id)->user->update([
            'password' => bcrypt($req->password1),
        ]);
        toastr()->success('Password Berhasil Diubah');
        return redirect('/admin/puskesmas');
    }

    public function searchcuti()
    {
        $puskesmas_id = Auth::user()->puskesmas->id;
        $search = request()->get('search');

        $data   = Cuti::where('puskesmas_id', $puskesmas_id)
            ->where('nama', 'LIKE', '%' . $search . '%')
            ->orWhere(function ($query) use ($search, $puskesmas_id) {
                $query->where('puskesmas_id', $puskesmas_id)->where('nip', 'LIKE', '%' . $search . '%');
            })->paginate(10);
        $data->appends(['search' => $search])->links();
        request()->flash();
        return view('puskesmas.cuti.index', compact('data'));
    }

    public function keDinkes($uuid)
    {
        $session_id = session()->get('uuid');
        $dinkes_id = Skpd::find(34)->user->id;
        if ($uuid == $session_id) {
            if (Auth::loginUsingId($dinkes_id)) {
                Session::forget('uuid');
                return redirect('/home/admin');
            }
        } else {
            toastr()->error('Kegagalan Sistem, harap hubungi programmer');
            return back();
        }
    }
}
