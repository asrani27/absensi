<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Lokasi;
use App\Models\Absensi;
use App\Models\Rentang;
use App\Models\Presensi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    public function pegawai()
    {
        return Auth::user()->pegawai;
    }

    public function checkVersion($version)
    {
        if ($version != 3) {
            //jika tidak sama please update
            $data['message_error'] = 300;
        } else {
            $data['message_error'] = 200;
        }
        return response()->json($data);
    }
    public function history($bulan, $tahun)
    {
        $hasil = Presensi::where('nip', Auth::user()->username)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->map(function ($item) {
            $item->jam_masuk = $item->jam_masuk == null ? '00:00' : Carbon::parse($item->jam_masuk)->format('H:i');
            $item->jam_pulang = $item->jam_pulang == null ? '00:00' : Carbon::parse($item->jam_pulang)->format('H:i');
            $tanggalFormat = Carbon::parse($item->tanggal);
            $item->tanggal = $tanggalFormat->translatedFormat('l') . ', ' . $tanggalFormat->format('d-m-Y');
            return $item;
        });

        $data['message_error'] = 200;
        $data['message']       = 'Data Ditemukan';
        $data['data']          = $hasil;
        return response()->json($data);
    }
    public function profil()
    {
        $profil = $this->pegawai();
        $profil['skpd'] = $this->pegawai()->skpd->nama;

        $data['message_error'] = 200;
        $data['message']       = 'Data Ditemukan';
        $data['data']          = $profil;
        return response()->json($data);
    }
    public function version()
    {
        $data['message_error'] = 200;
        $data['message']       = 'Data Ditemukan';
        $data['data']          = '1.0.8';

        return response()->json($data);
    }
    public function radius()
    {
        $pegawai = Auth::user()->pegawai;
        $presensi = Presensi::where('tanggal', Carbon::now()->format('Y-m-d'))->where('nip', $pegawai->nip)->first();

        $data['message_error'] = 200;
        $data['message']       = 'Data Ditemukan';
        $data['data']          = $presensi;

        return response()->json($data);
    }

    public function lokasiAbsen()
    {
        $lokasi = Auth::user()->pegawai->lokasipegawai;
        $data['message_error'] = 200;
        $data['message']       = 'Data Ditemukan';
        $data['data']          = $lokasi;
        return response()->json($data);
    }

    public function absenMasuk(Request $req)
    {
        $pegawai = Auth::user()->pegawai;
        $today = Carbon::now()->format('Y-m-d');
        $currentTime = Carbon::now()->format('H:i');

        // Batasan waktu absensi
        $startTime = '06:00';
        $startTime2 = '01:15';
        $endTime = '20:00';
        $endTime2 = '09:15';

        if ($req->id_lokasi == 1599) {
            if ($currentTime < $startTime2 || $currentTime > $endTime2) {
                return response()->json([
                    'message_error' => 200,
                    'message' => '25 Feb 2024, Hanya bisa absen mulai 08:45 s/d 09:15 WITA'
                ]);
            } else {

                $lokasi = Lokasi::find($req->id_lokasi);
                $myLocation['lat'] = $req->myLat;
                $myLocation['long'] = $req->myLong;
                $param['nip']               = $pegawai->nip;
                $param['skpd_id']           = $pegawai->skpd_id;
                $param['puskesmas_id']      = $pegawai->puskesmas_id;
                $param['sekolah_id']        = $pegawai->sekolah_id;
                $param['jenis_presensi']    = $pegawai->jenis_presensi;
                $param['latlong_masuk']     = json_encode($myLocation);
                $param['id_lokasi_masuk']   = $lokasi->id;
                $param['nama_lokasi_masuk'] = $lokasi->nama;
                $param['tanggal']           = $today;
                $param['jam_masuk_hari_besar'] = Carbon::now()->format('Y-m-d H:i:s');
                $param['request']           = $req->all();

                $check = Presensi::where('nip', $pegawai->nip)->where('tanggal', $today)->first();
                if ($check == null) {
                    Presensi::create($param);
                    $data['message_error'] = 200;
                    $data['message']       = 'Berhasil Di Simpan, presensi hari besar tidak di tampilkan di beranda anda';
                } else {
                    if ($check->jam_masuk_hari_besar == null || Carbon::parse($check->jam_masuk_hari_besar)->format('H:i:s') == '00:00:00') {
                        $check->update($param);
                        $data['message_error'] = 200;
                        $data['message']       = 'Presensi Masuk Berhasil Di Update, presensi hari besar tidak di tampilkan di beranda anda';
                    } else {
                        $data['message_error'] = 200;
                        $data['message']       = 'Anda Sudah Absen, presensi hari besar tidak di tampilkan di beranda anda';
                    }
                }

                return response()->json($data);
            }
        }

        if ($pegawai->jenis_presensi == 1) {
            if ($currentTime < $startTime || $currentTime > $endTime) {
                return response()->json([
                    'message_error' => 200,
                    'message' => 'Absen Masuk hanya bisa dilakukan antara jam 06:00 hingga 20:00'
                ]);
            }
        }

        $lokasi = Lokasi::find($req->id_lokasi);
        $myLocation['lat'] = $req->myLat;
        $myLocation['long'] = $req->myLong;
        $param['nip']               = $pegawai->nip;
        $param['skpd_id']           = $pegawai->skpd_id;
        $param['puskesmas_id']      = $pegawai->puskesmas_id;
        $param['sekolah_id']        = $pegawai->sekolah_id;
        $param['jenis_presensi']    = $pegawai->jenis_presensi;
        $param['latlong_masuk']     = json_encode($myLocation);
        $param['id_lokasi_masuk']   = $lokasi->id;
        $param['nama_lokasi_masuk'] = $lokasi->nama;
        $param['tanggal']           = $today;
        $param['jam_masuk']         = Carbon::now()->format('Y-m-d H:i:s');
        $param['request']           = $req->all();

        $check = Presensi::where('nip', $pegawai->nip)->where('tanggal', $today)->first();
        if ($check == null) {
            Presensi::create($param);
            $data['message_error'] = 200;
            $data['message']       = 'Berhasil Di Simpan';
        } else {
            if ($check->jam_masuk == null || Carbon::parse($check->jam_masuk)->format('H:i:s') == '00:00:00') {
                $check->update($param);
                $data['message_error'] = 200;
                $data['message']       = 'Presensi Masuk Berhasil Di Update';
            } else {
                $data['message_error'] = 200;
                $data['message']       = 'Anda Sudah Absen';
            }
        }

        return response()->json($data);
    }

    public function absenPulang(Request $req)
    {
        $pegawai = Auth::user()->pegawai;
        $today = Carbon::now()->format('Y-m-d');
        $currentTime = Carbon::now()->format('H:i');
        $day = Carbon::now()->format('F');


        // Batasan waktu absensi
        $startTime = '10.30 ';
        $endTime = '20:00';

        if ($pegawai->jenis_presensi == 1) {
            if ($currentTime < $startTime || $currentTime > $endTime) {
                return response()->json([
                    'message_error' => 200,
                    'message' => 'Absen Pulang hanya bisa dilakukan antara jam 16.30  hingga 20:00'
                ]);
            }
        }

        $lokasi = Lokasi::find($req->id_lokasi);
        $myLocation['lat'] = $req->myLat;
        $myLocation['long'] = $req->myLong;
        $param['nip']               = $pegawai->nip;
        $param['skpd_id']           = $pegawai->skpd_id;
        $param['puskesmas_id']      = $pegawai->puskesmas_id;
        $param['sekolah_id']        = $pegawai->sekolah_id;
        $param['jenis_presensi']    = $pegawai->jenis_presensi;
        $param['latlong_pulang']     = json_encode($myLocation);
        $param['id_lokasi_pulang']   = $lokasi->id;
        $param['nama_lokasi_pulang'] = $lokasi->nama;
        $param['tanggal']            = $today;
        $param['jam_pulang']         = Carbon::now()->format('Y-m-d H:i:s');
        $param['request']            = $req->all();

        $check = Presensi::where('nip', $pegawai->nip)->where('tanggal', $today)->first();
        if ($check == null) {
            Presensi::create($param);
            $data['message_error'] = 200;
            $data['message']       = 'Berhasil Di Simpan';
        } else {
            $check->update($param);
            $data['message_error'] = 200;
            $data['message']       = 'Presensi Pulang Berhasil Di Update';
            $data['data']          = $check;
        }

        return response()->json($data);
    }

    public function presensiSeminggu()
    {
        $nip = Auth::user()->pegawai->nip;
        $absensi = Presensi::where('nip', $nip)->orderBy('tanggal', 'DESC')->limit(7)->get()->map(function ($item) {
            $item->tanggal = Carbon::parse($item->tanggal)->format('d M Y');
            $item->jam_masuk = $item->jam_masuk == null ? null : Carbon::parse($item->jam_masuk)->format('H:i');
            $item->jam_pulang = $item->jam_pulang == null ? null : Carbon::parse($item->jam_pulang)->format('H:i');
            return $item;
        });

        $data['message_error'] = 200;
        $data['message']       = 'Data Ditemukan';
        $data['data']          = $absensi;
        return response()->json($data);
    }
    public function newPresensiSeminggu()
    {
        $nip = Auth::user()->pegawai->nip;
        $absensi = Presensi::where('nip', $nip)->orderBy('tanggal', 'DESC')->limit(7)->skip(1)->get()->map(function ($item, $key) {
            $item->tanggal = Carbon::parse($item->tanggal)->format('d M Y');
            $item->jam_masuk = $item->jam_masuk == null ? null : Carbon::parse($item->jam_masuk)->format('H:i');
            $item->jam_pulang = $item->jam_pulang == null ? null : Carbon::parse($item->jam_pulang)->format('H:i');
            return $item;
        })->where('tanggal', '!=', Carbon::now()->format('d M Y'));

        $data['message_error'] = 200;
        $data['message']       = 'Data Ditemukan!';
        $data['data']          = $absensi;
        return response()->json($data);
    }
    public function presensiToday()
    {
        $nip = Auth::user()->pegawai->nip;
        $absensi = Presensi::where('nip', $nip)->orderBy('tanggal', 'DESC')->limit(1)->get()->map(function ($item) {
            $item->tanggal = Carbon::parse($item->tanggal)->format('d M Y');
            $item->jam_masuk = $item->jam_masuk == null ? null : Carbon::parse($item->jam_masuk)->format('H:i');
            $item->jam_pulang = $item->jam_pulang == null ? null : Carbon::parse($item->jam_pulang)->format('H:i');
            return $item;
        })->first();

        $data['message_error'] = 200;
        $data['message']       = 'Data Ditemukan';
        $data['data']          = $absensi;
        return response()->json($data);
    }

    // public function storeRadius(Request $req)
    // {
    //     $today = Carbon::now();
    //     $hari  = $today->translatedFormat('l');
    //     $jam   = $today->format('H:i:s');

    //     $rentang = Rentang::where('hari', $hari)->first();

    //     if ($jam < $rentang->jam_masuk_selesai && $jam > $rentang->jam_masuk_mulai) {
    //         if ($req->button == 'pulang') {

    //             $data['message_error'] = 333;
    //             $data['message']       = 'Anda Berada Di Jam Masuk';
    //             $data['data']          = null;

    //             return response()->json($data);
    //         } else {
    //             if ($req->button == 'masuk') {

    //                 $date      = Carbon::now();
    //                 $tanggal   = $date->format('Y-m-d');
    //                 $jam_masuk = $date->format('H:i:s');

    //                 $check = Presensi::where('nip', $this->pegawai()->nip)->where('tanggal', $tanggal)->first();
    //                 if ($check == null) {
    //                     $attr['nip'] = $this->pegawai()->nip;
    //                     $attr['nama'] = $this->pegawai()->nama;
    //                     $attr['tanggal'] = $tanggal;
    //                     $attr['jam_masuk'] = $jam_masuk;
    //                     $attr['skpd_id'] = $this->pegawai()->skpd_id;
    //                     Presensi::create($attr);

    //                     $data['message_error'] = 200;
    //                     $data['message']       = 'Presensi Pulang Berhasil Disimpan';
    //                     $data['data']          = null;

    //                     return response()->json($data);
    //                 } else {
    //                     //Update Data
    //                     if ($check->jam_masuk == null) {
    //                         $check->update([
    //                             'jam_masuk' => $jam_masuk,
    //                         ]);
    //                         $data['message_error'] = 200;
    //                         $data['message']       = 'Presensi Masuk Berhasil Disimpan';
    //                         $data['data']          = null;

    //                         return response()->json($data);
    //                     } else {

    //                         $data['message_error'] = 200;
    //                         $data['message']       = 'Anda Sudah Melakukan Presensi Masuk';
    //                         $data['data']          = null;

    //                         return response()->json($data);
    //                     }
    //                 }
    //             } else {
    //                 //Presensi Pulang
    //                 $date      = Carbon::now();
    //                 $tanggal   = $date->format('Y-m-d');
    //                 $jam_pulang = $date->format('H:i:s');

    //                 $check = Presensi::where('nip', $this->pegawai()->nip)->where('tanggal', $tanggal)->first();
    //                 if ($check == null) {
    //                     $attr['nip'] = $this->pegawai()->nip;
    //                     $attr['nama'] = $this->pegawai()->nama;
    //                     $attr['tanggal'] = $tanggal;
    //                     $attr['jam_pulang'] = $jam_pulang;
    //                     $attr['skpd_id'] = $this->pegawai()->skpd_id;
    //                     Presensi::create($attr);

    //                     $data['message_error'] = 200;
    //                     $data['message']       = 'Presensi Pulang Berhasil Disimpan';
    //                     $data['data']          = null;

    //                     return response()->json($data);
    //                 } else {
    //                     $check->update([
    //                         'jam_pulang' => $jam_pulang,
    //                     ]);

    //                     $data['message_error'] = 200;
    //                     $data['message']       = 'Presensi Pulang Berhasil DiUpdate';
    //                     $data['data']          = null;

    //                     return response()->json($data);
    //                 }
    //             }
    //         }
    //     } elseif ($jam < $rentang->jam_pulang_selesai && $jam > $rentang->jam_pulang_mulai) {
    //         if ($req->button == 'masuk') {

    //             $data['message_error'] = 333;
    //             $data['message']       = 'Anda Berada Di Jam Pulang';
    //             $data['data']          = null;

    //             return response()->json($data);
    //         } else {
    //             if ($req->button == 'masuk') {

    //                 $date      = Carbon::now();
    //                 $tanggal   = $date->format('Y-m-d');
    //                 $jam_masuk = $date->format('H:i:s');

    //                 $check = Presensi::where('nip', $this->pegawai()->nip)->where('tanggal', $tanggal)->first();
    //                 if ($check == null) {
    //                     $attr['nip'] = $this->pegawai()->nip;
    //                     $attr['nama'] = $this->pegawai()->nama;
    //                     $attr['tanggal'] = $tanggal;
    //                     $attr['jam_masuk'] = $jam_masuk;
    //                     $attr['skpd_id'] = $this->pegawai()->skpd_id;
    //                     Presensi::create($attr);

    //                     $data['message_error'] = 200;
    //                     $data['message']       = 'Presensi Pulang Berhasil Disimpan';
    //                     $data['data']          = null;

    //                     return response()->json($data);
    //                 } else {
    //                     //Update Data
    //                     if ($check->jam_masuk == null) {
    //                         $check->update([
    //                             'jam_masuk' => $jam_masuk,
    //                         ]);
    //                         $data['message_error'] = 200;
    //                         $data['message']       = 'Presensi Masuk Berhasil Disimpan';
    //                         $data['data']          = null;

    //                         return response()->json($data);
    //                     } else {

    //                         $data['message_error'] = 200;
    //                         $data['message']       = 'Anda Sudah Melakukan Presensi Masuk';
    //                         $data['data']          = null;

    //                         return response()->json($data);
    //                     }
    //                 }
    //             } else {
    //                 //Presensi Pulang
    //                 $date      = Carbon::now();
    //                 $tanggal   = $date->format('Y-m-d');
    //                 $jam_pulang = $date->format('H:i:s');

    //                 $check = Presensi::where('nip', $this->pegawai()->nip)->where('tanggal', $tanggal)->first();
    //                 if ($check == null) {
    //                     $attr['nip'] = $this->pegawai()->nip;
    //                     $attr['nama'] = $this->pegawai()->nama;
    //                     $attr['tanggal'] = $tanggal;
    //                     $attr['jam_pulang'] = $jam_pulang;
    //                     $attr['skpd_id'] = $this->pegawai()->skpd_id;
    //                     Presensi::create($attr);

    //                     $data['message_error'] = 200;
    //                     $data['message']       = 'Presensi Pulang Berhasil Disimpan';
    //                     $data['data']          = null;

    //                     return response()->json($data);
    //                 } else {
    //                     $check->update([
    //                         'jam_pulang' => $jam_pulang,
    //                     ]);

    //                     $data['message_error'] = 200;
    //                     $data['message']       = 'Presensi Pulang Berhasil DiUpdate';
    //                     $data['data']          = $check;

    //                     return response()->json($data);
    //                 }
    //             }
    //         }
    //     } else {
    //         $data['message_error'] = 333;
    //         $data['message']       = 'Tidak Bisa Presensi Karena Di Luar Jam Presensi';
    //         $data['data']          = null;

    //         return response()->json($data);
    //     }
    // }
}
