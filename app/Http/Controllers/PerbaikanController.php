<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Jam;
use App\Models\Jam6;
use App\Models\Pegawai;
use App\Models\Presensi;
use App\Models\Ramadhan;
use App\Models\Perubahan;
use App\Models\JamRamadhan;
use App\Models\Jam6Ramadhan;
use Illuminate\Http\Request;
use App\Models\LiburNasional;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PerbaikanController extends Controller
{
    public function index()
    {
        $data = Perubahan::where('skpd_id', Auth::user()->skpd->id)->orderBy('id', 'DESC')->paginate(20);

        return view('admin.perubahan.index', compact('data'));
    }
    public function setujui($id)
    {
        $perubahan = Perubahan::find($id);

        //simpan data
        $dataawal = Presensi::where('nip', $perubahan->nip)->where('tanggal', $perubahan->tanggal)->first();

        if (LiburNasional::where('tanggal', $dataawal->tanggal)->first() != null) {
            $dataawal->update([
                'jam_masuk' => $dataawal->tanggal . ' 00:00:00',
                'jam_pulang' => $dataawal->tanggal . ' 00:00:00',
                'terlambat' => 0,
                'lebih_awal' => 0,
            ]);
            toastr()->error('Tanggal Ini termasuk Libur Nasional');
            return back();
        }
        $dataawal->update([
            'jam_masuk' => $perubahan->p_masuk,
            'jam_pulang' => $perubahan->p_pulang,
            'jenis_keterangan_id' => null,
        ]);

        //hitung terlambat/lebih awal
        $data = Presensi::where('nip', $perubahan->nip)->where('tanggal', $perubahan->tanggal)->first();

        $hari = Carbon::parse($data->tanggal)->translatedFormat('l');
        $pegawai = Pegawai::where('nip', $perubahan->nip)->first();

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

        toastr()->success('Data Disimpan');
        Perubahan::find($id)->update([
            'status' => 1,
        ]);
        return back();
    }

    public function tolak($id)
    {
        $perubahan = Perubahan::find($id);

        //simpan data
        $dataawal = Presensi::where('nip', $perubahan->nip)->where('tanggal', $perubahan->tanggal)->first();

        if (LiburNasional::where('tanggal', $dataawal->tanggal)->first() != null) {
            $dataawal->update([
                'jam_masuk' => $dataawal->tanggal . ' 00:00:00',
                'jam_pulang' => $dataawal->tanggal . ' 00:00:00',
                'terlambat' => 0,
                'lebih_awal' => 0,
            ]);
            toastr()->error('Tanggal Ini termasuk Libur Nasional');
            return back();
        }
        $dataawal->update([
            'jam_masuk' => $perubahan->masuk,
            'jam_pulang' => $perubahan->pulang,
            'jenis_keterangan_id' => null,
        ]);

        //hitung terlambat/lebih awal
        $data = Presensi::where('nip', $perubahan->nip)->where('tanggal', $perubahan->tanggal)->first();

        $hari = Carbon::parse($data->tanggal)->translatedFormat('l');
        $pegawai = Pegawai::where('nip', $perubahan->nip)->first();

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

        Perubahan::find($id)->update([
            'status' => 2,
        ]);
        toastr()->success('Disimpan');
        return back();
    }
    public function perubahandata()
    {
        $data = Perubahan::where('verifikator', Auth::user()->username)->orderBy('id', 'DESC')->paginate(20);

        return view('pegawai.perubahan.index', compact('data'));
    }

    public function perbaikan(Request $req, $id)
    {
        if (Auth::user()->skpd->kadis == null) {
            toastr()->error('Verifikator belum di isi, silahkan ke menu pegawai, klik verifikator, pilih verifikator dan simpan');
        } else {
            $data = Presensi::find($id);

            $validator = Validator::make($req->all(), [
                'file'  => 'mimes:jpg,png,jpeg,bmp,pdf|max:10240',
            ]);

            if ($validator->fails()) {
                $req->flash();
                toastr()->error('File harus Gambar dan Maks 10MB');
                return back();
            }

            if ($req->file == null) {
                $filename = null;
            } else {
                $extension = $req->file->getClientOriginalExtension();
                $filename = uniqid() . '.' . $extension;
                $image = $req->file('file');
                $realPath = public_path('storage') . '/perubahan';
                $image->move($realPath, $filename);
            }


            //simpan ke tabel perubahan
            $n = new Perubahan;
            $n->nip         = $data->nip;
            $n->tanggal     = $data->tanggal;
            $n->masuk       = $data->jam_masuk;
            $n->pulang      = $data->jam_pulang;
            $n->p_masuk     = $data->tanggal . ' ' . $req->jam_masuk;
            $n->p_pulang    = $data->tanggal . ' ' . $req->jam_pulang;
            $n->skpd_id     = Auth::user()->skpd->id;
            $n->keterangan  = $req->keterangan;
            $n->file        = $filename;
            $n->verifikator = Auth::user()->skpd->kadis;
            $n->status      = 0;
            $n->save();
            toastr()->success('Berhasil Di kirim');
        }
        return redirect('/admin/perubahandata');
    }
}
