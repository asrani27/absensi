<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Presensi;
use App\Models\Ringkasan;
use Illuminate\Http\Request;
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
                'persen_kehadiran' => round(($jml_jam - $terlambat - $lebih_awal) / $jml_jam * 100, 2),
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
                'persen_kehadiran' => round(($jml_jam - $terlambat - $lebih_awal) / $jml_jam * 100, 2),
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

        $ringkasan = Ringkasan::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->get();
        foreach ($ringkasan as $item) {
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

                //$hadirdiharikerja = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jam_masuk', '!=', '00:00:00')->orWhere('jam_pulang', '!=', '00:00:00')->get());

                $item->update([
                    'jumlah_hari' => $jml_hari,
                    'jumlah_jam' => $jml_jam,
                    'datang_lambat' => $terlambat,
                    'pulang_cepat' => $lebih_awal,
                    'persen_kehadiran' => round(($jml_jam - $terlambat - $lebih_awal) / $jml_jam * 100, 2),
                    's' => $countSakit + $countSakitKarenaCovid,
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
                    'persen_kehadiran' => round(($jml_jam - $terlambat - $lebih_awal) / $jml_jam * 100, 2),
                    's' => $countSakit + $countSakitKarenaCovid,
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

        foreach ($ringkasan as $item) {

            $masuk = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jam_masuk', '!=', null)->get());
            $pulang = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jam_pulang', '!=', null)->get());

            $item->update([
                'kerja' => $masuk,
                'masuk' => $masuk,
                'keluar' => $pulang,
            ]);
        }
        dd($ringkasan);
        toastr()->success('Selesai Di Hitung');
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

                //$hadirdiharikerja = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jam_masuk', '!=', '00:00:00')->orWhere('jam_pulang', '!=', '00:00:00')->get());

                $item->update([
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
                    'persen_kehadiran' => round(($jml_jam - $terlambat - $lebih_awal) / $jml_jam * 100, 2),
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

        foreach ($ringkasan as $item) {

            $masuk = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jam_masuk', '!=', '00:00:00')->get());
            $pulang = count(Presensi::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('jam_pulang', '!=', '00:00:00')->get());
            //dd($hadirdiharikerja, $item->nama);
            $item->update([
                'kerja' => $masuk,
                'masuk' => $masuk,
                'keluar' => $pulang,
            ]);
        }

        toastr()->success('Selesai Di Hitung');
        return back();
    }
}
