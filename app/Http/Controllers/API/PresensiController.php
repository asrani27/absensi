<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
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

        return response()->json('tesr');
        // $lokasi = Auth::user()->pegawai->skpd->lokasi;
        // $data['message_error'] = 200;
        // $data['message']       = 'Data Ditemukan';
        // $data['data']          = $lokasi;
        // return response()->json($data);
    }

    public function absenMasuk(Request $req)
    {
        $attr = $req->all();
        return response()->json($attr);
    }

    public function absenPulang(Request $req)
    {
        $attr = $req->all();
        return response()->json($attr);
    }

    public function presensiSeminggu()
    {
        $nip = Auth::user()->pegawai->nip;
        $absensi = Absensi::where('nip', $nip)->orderBy('tanggal', 'DESC')->limit(7)->get()->map(function ($item) {
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

    public function storeRadius(Request $req)
    {
        $today = Carbon::now();
        $hari  = $today->translatedFormat('l');
        $jam   = $today->format('H:i:s');

        $rentang = Rentang::where('hari', $hari)->first();

        if ($jam < $rentang->jam_masuk_selesai && $jam > $rentang->jam_masuk_mulai) {
            if ($req->button == 'pulang') {

                $data['message_error'] = 333;
                $data['message']       = 'Anda Berada Di Jam Masuk';
                $data['data']          = null;

                return response()->json($data);
            } else {
                if ($req->button == 'masuk') {

                    $date      = Carbon::now();
                    $tanggal   = $date->format('Y-m-d');
                    $jam_masuk = $date->format('H:i:s');

                    $check = Presensi::where('nip', $this->pegawai()->nip)->where('tanggal', $tanggal)->first();
                    if ($check == null) {
                        $attr['nip'] = $this->pegawai()->nip;
                        $attr['nama'] = $this->pegawai()->nama;
                        $attr['tanggal'] = $tanggal;
                        $attr['jam_masuk'] = $jam_masuk;
                        $attr['skpd_id'] = $this->pegawai()->skpd_id;
                        Presensi::create($attr);

                        $data['message_error'] = 200;
                        $data['message']       = 'Presensi Pulang Berhasil Disimpan';
                        $data['data']          = null;

                        return response()->json($data);
                    } else {
                        //Update Data
                        if ($check->jam_masuk == null) {
                            $check->update([
                                'jam_masuk' => $jam_masuk,
                            ]);
                            $data['message_error'] = 200;
                            $data['message']       = 'Presensi Masuk Berhasil Disimpan';
                            $data['data']          = null;

                            return response()->json($data);
                        } else {

                            $data['message_error'] = 200;
                            $data['message']       = 'Anda Sudah Melakukan Presensi Masuk';
                            $data['data']          = null;

                            return response()->json($data);
                        }
                    }
                } else {
                    //Presensi Pulang
                    $date      = Carbon::now();
                    $tanggal   = $date->format('Y-m-d');
                    $jam_pulang = $date->format('H:i:s');

                    $check = Presensi::where('nip', $this->pegawai()->nip)->where('tanggal', $tanggal)->first();
                    if ($check == null) {
                        $attr['nip'] = $this->pegawai()->nip;
                        $attr['nama'] = $this->pegawai()->nama;
                        $attr['tanggal'] = $tanggal;
                        $attr['jam_pulang'] = $jam_pulang;
                        $attr['skpd_id'] = $this->pegawai()->skpd_id;
                        Presensi::create($attr);

                        $data['message_error'] = 200;
                        $data['message']       = 'Presensi Pulang Berhasil Disimpan';
                        $data['data']          = null;

                        return response()->json($data);
                    } else {
                        $check->update([
                            'jam_pulang' => $jam_pulang,
                        ]);

                        $data['message_error'] = 200;
                        $data['message']       = 'Presensi Pulang Berhasil DiUpdate';
                        $data['data']          = null;

                        return response()->json($data);
                    }
                }
            }
        } elseif ($jam < $rentang->jam_pulang_selesai && $jam > $rentang->jam_pulang_mulai) {
            if ($req->button == 'masuk') {

                $data['message_error'] = 333;
                $data['message']       = 'Anda Berada Di Jam Pulang';
                $data['data']          = null;

                return response()->json($data);
            } else {
                if ($req->button == 'masuk') {

                    $date      = Carbon::now();
                    $tanggal   = $date->format('Y-m-d');
                    $jam_masuk = $date->format('H:i:s');

                    $check = Presensi::where('nip', $this->pegawai()->nip)->where('tanggal', $tanggal)->first();
                    if ($check == null) {
                        $attr['nip'] = $this->pegawai()->nip;
                        $attr['nama'] = $this->pegawai()->nama;
                        $attr['tanggal'] = $tanggal;
                        $attr['jam_masuk'] = $jam_masuk;
                        $attr['skpd_id'] = $this->pegawai()->skpd_id;
                        Presensi::create($attr);

                        $data['message_error'] = 200;
                        $data['message']       = 'Presensi Pulang Berhasil Disimpan';
                        $data['data']          = null;

                        return response()->json($data);
                    } else {
                        //Update Data
                        if ($check->jam_masuk == null) {
                            $check->update([
                                'jam_masuk' => $jam_masuk,
                            ]);
                            $data['message_error'] = 200;
                            $data['message']       = 'Presensi Masuk Berhasil Disimpan';
                            $data['data']          = null;

                            return response()->json($data);
                        } else {

                            $data['message_error'] = 200;
                            $data['message']       = 'Anda Sudah Melakukan Presensi Masuk';
                            $data['data']          = null;

                            return response()->json($data);
                        }
                    }
                } else {
                    //Presensi Pulang
                    $date      = Carbon::now();
                    $tanggal   = $date->format('Y-m-d');
                    $jam_pulang = $date->format('H:i:s');

                    $check = Presensi::where('nip', $this->pegawai()->nip)->where('tanggal', $tanggal)->first();
                    if ($check == null) {
                        $attr['nip'] = $this->pegawai()->nip;
                        $attr['nama'] = $this->pegawai()->nama;
                        $attr['tanggal'] = $tanggal;
                        $attr['jam_pulang'] = $jam_pulang;
                        $attr['skpd_id'] = $this->pegawai()->skpd_id;
                        Presensi::create($attr);

                        $data['message_error'] = 200;
                        $data['message']       = 'Presensi Pulang Berhasil Disimpan';
                        $data['data']          = null;

                        return response()->json($data);
                    } else {
                        $check->update([
                            'jam_pulang' => $jam_pulang,
                        ]);

                        $data['message_error'] = 200;
                        $data['message']       = 'Presensi Pulang Berhasil DiUpdate';
                        $data['data']          = $check;

                        return response()->json($data);
                    }
                }
            }
        } else {
            $data['message_error'] = 333;
            $data['message']       = 'Tidak Bisa Presensi Karena Di Luar Jam Presensi';
            $data['data']          = null;

            return response()->json($data);
        }
    }
}
