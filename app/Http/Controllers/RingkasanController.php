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
        $checkDataPegawai = Pegawai::where('nip', $req->nip)->first();
        if ($checkDataPegawai == null) {
            toastr()->error('Tidak Ada data Di Absensi');
            return back();
        } else {
            $check = Ringkasan::where('nip', $req->nip)->where('bulan', $req->bulan)->where('tahun', $req->tahun)->where('skpd_id', Auth::user()->skpd->id)->first();
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

                toastr()->error('NIP Sudah ada');
                return back();
            }
        }
    }
    public function delete($id)
    {
        Ringkasan::find($id)->delete();
        toastr()->success('Berhasil Di Hapus');
        return back();
    }

    public function masukkanPegawai($bulan, $tahun)
    {
        $skpd_id = Auth::user()->skpd->id;
        $pegawai = Pegawai::where('skpd_id', $skpd_id)->where('puskesmas_id', null)->where('is_aktif', 1)->get();
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
                ]);
            }
        }
        toastr()->success('Berhasil Di Masukkan');
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

    public function hitungSemua($bulan, $tahun)
    {
        //dd($bulan, $tahun);
        $ringkasan = Ringkasan::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->get();
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
                ]);
                toastr()->success('Berhasil Di Hitung');
                return back();
            } else {
            }
        }

        toastr()->success('Selesai Di Hitung');
        return back();
    }
}
