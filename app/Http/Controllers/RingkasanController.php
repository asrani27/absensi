<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pegawai;
use App\Models\Presensi;
use App\Models\Ringkasan;
use Illuminate\Http\Request;
use App\Models\LiburNasional;
use Illuminate\Support\Facades\Auth;

class RingkasanController extends Controller
{
    public function tambahPegawai(Request $req)
    {
        if (kunciSkpd(Auth::user()->skpd->id, $req->bulan, $req->tahun) == 1) {
            toastr()->error('Data Bulan Ini telah di kunci dan tidak bisa di ubah');
            return back();
        }

        $checkDataPegawai = Pegawai::where('nip', $req->nip)->first();
        if ($checkDataPegawai == null) {
            toastr()->error('Tidak Ada data Di Absensi');
            return back();
        } else {
            $check = Ringkasan::where('nip', $req->nip)->where('bulan', $req->bulan)->where('tahun', $req->tahun)->first();
            //dd($check);
            if ($check == null) {
                $n = new Ringkasan;
                $n->nip = $req->nip;
                $n->nama = $checkDataPegawai->nama;
                $n->jabatan = $req->jabatan;
                $n->skpd_id = Auth::user()->skpd->id;
                $n->bulan = $req->bulan;
                $n->tahun = $req->tahun;
                $n->save();
                toastr()->success('Berhasil Di Tambahkan');
                return back();
            } else {
                if (Auth::user()->skpd->id == $check->skpd_id) {
                    toastr()->error('NIP Sudah ada dalam laporan');
                    return back();
                } else {
                    $check->update([
                        'skpd_id' => Auth::user()->skpd->id,
                    ]);
                    toastr()->success('Berhasil Di Tambahkan');
                    return back();
                }
            }
        }
    }

    public function delete($id, $bulan, $tahun)
    {
        if (kunciSkpd(Auth::user()->skpd->id, $bulan, $tahun) == 1) {
            toastr()->error('Data Bulan Ini telah di kunci dan tidak bisa di ubah');
            return back();
        }

        Ringkasan::find($id)->delete();
        toastr()->success('Berhasil Di Hapus');
        return back();
    }

    public function hitung($id, $bulan, $tahun)
    {
        if (kunciSkpd(Auth::user()->skpd->id, $bulan, $tahun) == 1) {
            toastr()->error('Data Bulan Ini telah di kunci dan tidak bisa di ubah');
            return back();
        }
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
                //'persen_kehadiran' => round(($jml_jam - $terlambat - $lebih_awal) / $jml_jam * 100, 2),
                's' => $countSakit + $countSakitKarenaCovid,
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
                //'persen_kehadiran' => round(($jml_jam - $terlambat - $lebih_awal) / $jml_jam * 100, 2),
                's' => $countSakit + $countSakitKarenaCovid,
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

    public function nol($id, $bulan, $tahun)
    {
        if (kunciSkpd(Auth::user()->skpd->id, $bulan, $tahun) == 1) {
            toastr()->error('Data Bulan Ini telah di kunci dan tidak bisa di ubah');
            return back();
        }
        Ringkasan::find($id)->update([
            'jumlah_hari' => 0,
            'jumlah_jam' => 0,
            'kerja' => 0,
            'libur' => 0,
            'masuk' => 0,
            'keluar' => 0,
            'datang_lambat' => 0,
            'pulang_cepat' => 0,
            'persen_kehadiran' => 0,
            'total_hari_kerja' => 0,
            's' => 0,
            'tr' => 0,
            'd' => 0,
            'c' => 0,
            'l' => 0,
            'i' => 0,
            'a' => 0,
            'o' => 0,
        ]);
        toastr()->success('Data di 0 kan');
        return back();
    }

    public function masukkanPegawai($bulan, $tahun)
    {

        if (kunciSkpd(Auth::user()->skpd->id, $bulan, $tahun) == 1) {
            toastr()->error('Data Bulan Ini telah di kunci dan tidak bisa di ubah');
            return back();
        }

        $skpd_id = Auth::user()->skpd->id;
        $pegawai = Pegawai::where('skpd_id', $skpd_id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('is_aktif', 1)->get();

        foreach ($pegawai as $item) {
            $check = Ringkasan::where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            if ($check == null) {
                $n = new Ringkasan;
                $n->nip = $item->nip;
                $n->nama = $item->nama;
                $n->jabatan = $item->jabatan;
                $n->skpd_id = $skpd_id;
                $n->bulan = $bulan;
                $n->tahun = $tahun;
                $n->save();
            } else {
                $check->update([
                    'jabatan' => $item->jabatan,
                    'skpd_id' => $skpd_id,
                ]);
            }
        }
        toastr()->success('Berhasil Di Masukkan');
        return back();
    }

    public function hitungSemua($bulan, $tahun)
    {
        if (kunciSkpd(Auth::user()->skpd->id, $bulan, $tahun) == 1) {
            toastr()->error('Data Bulan Ini telah di kunci dan tidak bisa di ubah');
            return back();
        }

        $ringkasan = Ringkasan::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->where('nip', '197508312010011005')->get();
        //dd($ringkasan);
        foreach ($ringkasan as $item) {
            if (Pegawai::where('nip', $item->nip)->first()->jenis_presensi == 1) {
                $jml_hari   = jumlahHari($bulan, $tahun)['jumlah_hari'];
                $jml_jam    = jumlahHari($bulan, $tahun)['jumlah_jam'];
                $terlambat  = telat($item->nip, $bulan, $tahun)->sum('terlambat');
                $lebih_awal = telat($item->nip, $bulan, $tahun)->sum('lebih_awal');

                $countSakit = Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 3)->get()->map(function ($item) {
                    $item->libur = Carbon::parse($item->tanggal)->isWeekend();
                    return $item;
                })->where('libur', false)->count();
                $countSakitKarenaCovid = Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 9)->get()->map(function ($item) {
                    $item->libur = Carbon::parse($item->tanggal)->isWeekend();
                    return $item;
                })->where('libur', false)->count();
                $countCutiTahun = Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 7)->get()->map(function ($item) {
                    $item->libur = Carbon::parse($item->tanggal)->isWeekend();
                    return $item;
                })->where('libur', false)->count();
                $countCutiLain = Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 8)->get()->map(function ($item) {
                    $item->libur = Carbon::parse($item->tanggal)->isWeekend();
                    return $item;
                })->where('libur', false)->count();
                $countTraining = Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 4)->get()->map(function ($item) {
                    $item->libur = Carbon::parse($item->tanggal)->isWeekend();
                    return $item;
                })->where('libur', false)->count();
                $countTugas = Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 5)->get()->map(function ($item) {
                    $item->libur = Carbon::parse($item->tanggal)->isWeekend();
                    return $item;
                })->where('libur', false)->count();
                $countIzin = Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 6)->get()->map(function ($item) {
                    $item->libur = Carbon::parse($item->tanggal)->isWeekend();
                    return $item;
                })->where('libur', false)->count();
                $countAlpa = Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jenis_keterangan_id', 1)->get()->map(function ($item) {
                    $item->libur = Carbon::parse($item->tanggal)->isWeekend();
                    return $item;
                })->where('libur', false)->count();

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

                //$hadirdiharikerja = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jam_masuk', '!=', '00:00:00')->orWhere('jam_pulang', '!=', '00:00:00')->get());

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

    public function hitungtotalharikerja($bulan, $tahun)
    {
        if (kunciSkpd(Auth::user()->skpd->id, $bulan, $tahun) == 1) {
            toastr()->error('Data Bulan Ini telah di kunci dan tidak bisa di ubah');
            return back();
        }

        $ringkasan = Ringkasan::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->get();

        $cutibersama = LiburNasional::whereMonth('tanggal', $bulan)->where('deskripsi', '=', 'cuti bersama')->whereYear('tanggal', $tahun)->get()->count();
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
                'masuk' => $masuk + $cutibersama,
                'keluar' => $pulang + $cutibersama,
            ]);
        }

        toastr()->success('Selesai Di Hitung');
        return back();
    }

    public function persenakhir($bulan, $tahun)
    {
        if (kunciSkpd(Auth::user()->skpd->id, $bulan, $tahun) == 1) {
            toastr()->error('Data Bulan Ini telah di kunci dan tidak bisa di ubah');
            return back();
        }

        $ringkasan = Ringkasan::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->get();
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
                    $updatepersen = $persen > 100 ? 100 : $persen;
                }
                $item->update(['persen_kehadiran' => $updatepersen]);
            } catch (\Exception $e) {
                $item->update(['persen_kehadiran' => 0]);
            }
        }
        toastr()->success('Persentase Selesai');
        return back();
    }

    public function persenakhirsekolah($bulan, $tahun)
    {
        if (kunciSkpd(Auth::user()->skpd->id, $bulan, $tahun) == 1) {
            toastr()->error('Data Bulan Ini telah di kunci dan tidak bisa di ubah');
            return back();
        }

        $ringkasan = Ringkasan::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', '!=', null)->where('bulan', $bulan)->where('tahun', $tahun)->get();
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

            try {
                $persen = round((($hadir / $jumlahhari) * 100), 2) - $kurangi_persen_terlambat - $kurangi_persen_pulangcepat;
                if ($persen < 0) {
                    $updatepersen = 0;
                } else {
                    $updatepersen = $persen > 100 ? 100 : $persen;
                }
                $item->update(['persen_kehadiran' => $updatepersen]);
            } catch (\Exception $e) {
                $item->update(['persen_kehadiran' => 0]);
            }
        }
        toastr()->success('Persentase Selesai');
        return back();
    }

    public function seratuspersen($bulan, $tahun)
    {
        $ringkasan = Ringkasan::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', '!=', null)->where('bulan', $bulan)->where('tahun', $tahun)->get();
        foreach ($ringkasan as $item) {
            $item->update(['persen_kehadiran' => 100]);
        }
        toastr()->success('Persentase Selesai');
        return back();
    }

    public function masukkanPegawaiSekolah($bulan, $tahun)
    {

        if (kunciSkpd(Auth::user()->skpd->id, $bulan, $tahun) == 1) {
            toastr()->error('Data Bulan Ini telah di kunci dan tidak bisa di ubah');
            return back();
        }

        $skpd_id = Auth::user()->skpd->id;
        $pegawai = Pegawai::where('skpd_id', $skpd_id)->where('puskesmas_id', null)->where('sekolah_id', '!=', null)->where('is_aktif', 1)->get();

        foreach ($pegawai as $item) {
            $check = Ringkasan::where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            if ($check == null) {
                $n = new Ringkasan;
                $n->nip = $item->nip;
                $n->nama = $item->nama;
                $n->jabatan = $item->jabatan;
                $n->skpd_id = $skpd_id;
                $n->sekolah_id = $item->sekolah_id;
                $n->bulan = $bulan;
                $n->tahun = $tahun;
                $n->save();
            } else {
                $check->update([
                    'jabatan' => $item->jabatan,
                    'sekolah_id' => $item->sekolah_id,
                    'skpd_id' => $skpd_id,
                ]);
            }
        }
        toastr()->success('Berhasil Di Masukkan');
        return back();
    }

    public function hitungSemuaSekolah($bulan, $tahun)
    {
        if (kunciSkpd(Auth::user()->skpd->id, $bulan, $tahun) == 1) {
            toastr()->error('Data Bulan Ini telah di kunci dan tidak bisa di ubah');
            return back();
        }

        $ringkasan = Ringkasan::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', '!=', null)->where('bulan', $bulan)->where('tahun', $tahun)->get();
        foreach ($ringkasan as $item) {
            if (Pegawai::where('nip', $item->nip)->first()->jenis_presensi == 1 || Pegawai::where('nip', $item->nip)->first()->jenis_presensi == 4) {
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

                //$hadirdiharikerja = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jam_masuk', '!=', '00:00:00')->orWhere('jam_pulang', '!=', '00:00:00')->get());

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

                //$hadirdiharikerja = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jam_masuk', '!=', '00:00:00')->orWhere('jam_pulang', '!=', '00:00:00')->get());

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

    public function hitungtotalharikerjaSekolah($bulan, $tahun)
    {
        if (kunciSkpd(Auth::user()->skpd->id, $bulan, $tahun) == 1) {
            toastr()->error('Data Bulan Ini telah di kunci dan tidak bisa di ubah');
            return back();
        }

        $ringkasan = Ringkasan::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', '!=', null)->where('bulan', $bulan)->where('tahun', $tahun)->get();

        $cutibersama = LiburNasional::whereMonth('tanggal', $bulan)->where('deskripsi', '=', 'cuti bersama')->whereYear('tanggal', $tahun)->get()->count();
        foreach ($ringkasan as $item) {

            // $masuk = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jam_masuk', '!=', '00:00:00')->get());
            // $pulang = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jam_pulang', '!=', '00:00:00')->get());
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
                'masuk' => $masuk + $cutibersama,
                'keluar' => $pulang + $cutibersama,
            ]);
        }

        toastr()->success('Selesai Di Hitung');
        return back();
    }
}
