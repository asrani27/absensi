<?php

namespace App\Http\Controllers;

use Alert;
use App\Models\Qr;
use Carbon\Carbon;
use App\Models\Skpd;
use App\Models\Lokasi;
use GuzzleHttp\Client;
use App\Models\Rentang;
use App\Models\Presensi;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Jobs\PresensiProcessMasuk;
use Illuminate\Support\Facades\DB;
use App\Jobs\PresensiProcessPulang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PresensiController extends Controller
{

    public function skpd()
    {
        $client = new Client(['base_uri' => 'https://tpp.banjarmasinkota.go.id/api/pegawai/']);
        $response = $client->request('get', Auth::user()->username);
        return  json_decode((string) $response->getBody())->data;
    }
    public function masuk()
    {
        $skpd = "-";
        $lat        = (float)$skpd->lat;
        $long       = (float)$skpd->long;
        $radius     = (float)$skpd->radius;
        $latlong2 = [
            'lat' => $lat,
            'lng' => $long
        ];
        return view('pegawai.presensi.masuk', compact('skpd', 'latlong2'));
    }

    public function pulang()
    {
        return view('pegawai.presensi.pulang');
    }

    public function storeMasuk(Request $req)
    {
        $datetime = Carbon::now()->format('Y-m-d H:i:s');
        $attr['jam_masuk'] = $datetime;
        $attr['nip'] = Auth::user()->username;
        $attr['skpd_id'] = $this->skpd()->skpd_id;
        $radius = Skpd::find($this->skpd()->skpd_id)->radius;

        if ((float)$req->jarak * 1000 > $radius) {
            toastr()->error('Maaf Anda Di luar jangkauan radius presensi');
            return back();
        } else {
            $check = Presensi::where('nip', $attr['nip'])->where('jam_masuk', 'like', '%' . Carbon::today()->format('Y-m-d') . '%')->first();

            if ($check == null) {
                $attr['photo_masuk'] = $req->photo;
                Presensi::create($attr);
                toastr()->success('Berhasil Disimpan');
                return redirect('/pegawai/presensi/masuk');
            } else {
                $attr['photo_pulang'] = $req->photo;
                $check->update($attr);
                toastr()->success('Berhasil Diupdate');
                return redirect('/pegawai/presensi/masuk');
            }
        }
    }

    public function radius()
    {
        // $client = new Client(['base_uri' => 'https://tpp.banjarmasinkota.go.id/api/pegawai/']);
        // $response = $client->request('get', Auth::user()->username);
        // $data =  json_decode((string) $response->getBody())->data;
        // $skpd = Skpd::find($data->skpd_id);
        $skpd = Skpd::find(Auth::user()->pegawai->skpd_id);
        if (Auth::user()->pegawai->lokasi == null) {
            $latlong2 = null;
        } else {
            $lokasi = Auth::user()->pegawai->lokasi;
            $lat        = (float)$skpd->lat;
            $long       = (float)$skpd->long;
            $radius     = (float)$skpd->radius;
            $latlong2 = [
                'lat' => $lokasi->lat,
                'lng' => $lokasi->long
            ];
        }

        $check = Presensi::where('nip', $this->pegawai()->nip)->where('tanggal', Carbon::today()->format('Y-m-d'))->first();
        if ($check == null) {
            $jam_masuk = '00:00:00';
            $jam_pulang = '00:00:00';
        } else {
            $jam_masuk = $check->jam_masuk;
            $jam_pulang = $check->jam_pulang;
        }

        $agent = new Agent();
        $os = $agent->browser();

        $hari  = Carbon::now()->translatedFormat('l');

        $pilih_lokasi = Auth::user()->pegawai->lokasipegawai;

        //$pilih_lokasi = Lokasi::where('skpd_id', Auth::user()->pegawai->skpd_id)->get();
        $rentang = Rentang::where('hari', $hari)->first();
        // if ($os == 'Safari') {
        //     return view('pegawai.presensi.radius.presensi', compact('skpd', 'latlong2', 'jam_masuk', 'jam_pulang', 'os', 'rentang'));
        // } else {
        return view('pegawai.presensi.radius.presensi2', compact('skpd', 'pilih_lokasi', 'latlong2', 'jam_masuk', 'jam_pulang', 'rentang', 'os'));
        // }
    }

    public function barcode()
    {
        $client = new Client(['base_uri' => 'https://tpp.banjarmasinkota.go.id/api/pegawai/']);
        $response = $client->request('get', Auth::user()->username);
        $data =  json_decode((string) $response->getBody())->data;
        $skpd = Skpd::find($data->skpd_id);
        if (Auth::user()->pegawai->lokasi == null) {
            $latlong2 = null;
        } else {
            $lokasi = Auth::user()->pegawai->lokasi;
            $lat        = (float)$skpd->lat;
            $long       = (float)$skpd->long;
            $radius     = (float)$skpd->radius;
            $latlong2 = [
                'lat' => $lokasi->lat,
                'lng' => $lokasi->long
            ];
        }
        return view('pegawai.presensi.barcode.presensi', compact('skpd', 'latlong2'));
        //return view('pegawai.presensi.barcode.scan',compact('skpd','latlong2'));
    }

    public function frontCamera()
    {
        $client = new Client(['base_uri' => 'https://tpp.banjarmasinkota.go.id/api/pegawai/']);
        $response = $client->request('get', Auth::user()->username);
        $data =  json_decode((string) $response->getBody())->data;
        $skpd = Skpd::find($data->skpd_id);
        if (Auth::user()->pegawai->lokasi == null) {
            $latlong2 = null;
        } else {
            $lokasi = Auth::user()->pegawai->lokasi;
            $lat        = (float)$skpd->lat;
            $long       = (float)$skpd->long;
            $radius     = (float)$skpd->radius;
            $latlong2 = [
                'lat' => $lokasi->lat,
                'lng' => $lokasi->long
            ];
        }
        return view('pegawai.presensi.barcode.front', compact('skpd', 'latlong2'));
    }

    public function backCamera()
    {
        $client = new Client(['base_uri' => 'https://tpp.banjarmasinkota.go.id/api/pegawai/']);
        $response = $client->request('get', Auth::user()->username);
        $data =  json_decode((string) $response->getBody())->data;
        $skpd = Skpd::find($data->skpd_id);
        if (Auth::user()->pegawai->lokasi == null) {
            $latlong2 = null;
        } else {
            $lokasi = Auth::user()->pegawai->lokasi;
            $lat        = (float)$skpd->lat;
            $long       = (float)$skpd->long;
            $radius     = (float)$skpd->radius;
            $latlong2 = [
                'lat' => $lokasi->lat,
                'lng' => $lokasi->long
            ];
        }
        return view('pegawai.presensi.barcode.back', compact('skpd', 'latlong2'));
    }

    public function manual()
    {
        $client = new Client(['base_uri' => 'https://tpp.banjarmasinkota.go.id/api/pegawai/']);
        $response = $client->request('get', Auth::user()->username);
        $data =  json_decode((string) $response->getBody())->data;
        $skpd = Skpd::find($data->skpd_id);

        $presensi = Presensi::where('tanggal', Carbon::now()->format('Y-m-d'))->where('nip', $this->pegawai()->nip)->first();

        return view('pegawai.presensi.manual.presensi', compact('skpd', 'presensi'));
    }

    public function pegawai()
    {
        return Auth::user()->pegawai;
    }

    public function scanBarcode(Request $req)
    {
        $checkQr = Qr::where('qrcode', $req->qrcode)->where('skpd_id', $this->pegawai()->skpd_id)->first();
        if ($checkQr == null) {
            toastr()->error('Qrcode Tidak Ada Dalam Database');
            return back();
        } else {
            if ($checkQr->tanggal != Carbon::now()->format('Y-m-d')) {
                toastr()->error('Qrcode Ini Telah Kadaluwarsa');
                return back();
            } else {
                $today = Carbon::now()->format('Y-m-d');
                $time  = Carbon::now()->format('H:i:s');
                $check = Presensi::where('tanggal', $today)->where('nip', Auth::user()->username)->first();
                if ($check == null) {
                    toastr()->error('Tidak Ada Data');
                } else {
                    if ($req->jenis == 'masuk') {
                        //presensi masuk
                        if ($check->jam_masuk != null) {
                            toastr()->error('Anda Sudah melakukan presensi masuk');
                        } else {
                            toastr()->success('Presensi Berhasil Di Simpan');
                            $check->update(['jam_masuk' => $time]);
                        }
                    } else {
                        //presensi pulang
                        toastr()->success('Presensi Pulang Berhasil Di Simpan');
                        $check->update(['jam_pulang' => $time]);
                    }
                }
                return back();
            }
        }
    }

    public function storeManual(Request $req)
    {
        $check = Presensi::where('nip', Auth::user()->username)->where('tanggal', Carbon::today()->format('Y-m-d'))->first();
        if ($check == null) {
            $attr['nip'] = Auth::user()->username;
            $attr['tanggal'] = Carbon::today()->format('Y-m-d');
            $attr['keterangan'] = $req->keterangan;

            Presensi::create($attr);
            toastr()->success('Berhasil Di Kirim Ke Admin');
        } else {
            if ($check->jam_masuk == null || $check->jam_keluar == null) {

                $validator = Validator::make($req->all(), [
                    'file' => 'mimes:pdf,docx,png,jpg,jpeg|max:8048'
                ]);

                if ($validator->fails()) {
                    toastr()->error('File Harus Berupa pdf/docx/png/jpg/jpeg');
                    return back();
                }

                if ($req->hasFile('file')) {
                    $filename = $req->file->getClientOriginalName();
                    $filename = date('d-m-Y-') . rand(1, 9999) . $filename;

                    $req->file->storeAs('/public/' . Auth::user()->username . '/presensi/manual', $filename);
                }

                if ($req->file == null) {
                    $check->update([
                        'keterangan' => $req->keterangan,
                    ]);
                } else {
                    $check->update([
                        'file' => $filename,
                        'keterangan' => $req->keterangan,
                    ]);
                }
                toastr()->success('Berhasil Di Kirim Ke Admin');
            } else {
                toastr()->error('Anda Sudah Mengirim data pada tanggal ini');
            }
        }
        return back();
    }

    public function checkJam($req)
    {
        $today = Carbon::now();
        $hari  = $today->translatedFormat('l');
        $jam   = $today->format('H:i:s');
        $rentang = Rentang::where('hari', $hari)->first();

        if ($jam < $rentang->jam_masuk_selesai && $jam > $rentang->jam_masuk_mulai) {
            return 'masuk';
        } elseif ($jam < $rentang->jam_pulang_selesai && $jam > $rentang->jam_pulang_mulai) {
            return 'pulang';
        } else {
            return false;
        }
    }

    public function storeRadius(Request $req)
    {
        $lat2 = Lokasi::find($req->lokasi_id)->lat;
        $long2 = Lokasi::find($req->lokasi_id)->long;
        $distance = distance($req->lat, $req->long, $lat2, $long2, "K");
        $nama_lokasi = Lokasi::find($req->lokasi_id)->nama;

        if ($distance > 1) {
            alert()->error('Jarak ke ' . $nama_lokasi . ', ' . $distance . ' KM, harus mencakup 100 meter');
            return back();
        }

        if ($this->checkJam($req) == 'masuk') {
            PresensiProcessMasuk::dispatch();
            alert()->success('Presensi Masuk Berhasil DiSimpan');
            return redirect('/home/pegawai');
        } elseif ($this->checkJam($req) == 'pulang') {
            PresensiProcessPulang::dispatch();
            alert()->success('Presensi Pulang Berhasil DiSimpan');
            return redirect('/home/pegawai');
        } else {
            alert()->error('Di Luar Jam Presensi');
            return back();
        }
    }

    public function simpanAndroidMasuk($req, $jenis)
    {
        PresensiProcessMasuk::dispatch($jenis);
    }

    public function simpanAndroidPulang($jenis)
    {
        PresensiProcessPulang::dispatch($jenis);
    }

    public function androidMasuk()
    {
        $date      = Carbon::now();
        $tanggal   = $date->format('Y-m-d');
        $check = Presensi::where('nip', $this->pegawai()->nip)->where('tanggal', $tanggal)->first();
        if ($check == null) {
            return 'simpan';
        } else {
            if ($check->jam_masuk == null) {
                return 'update';
            } else {
                return false;
            }
        }
    }

    public function androidPulang()
    {
        $date      = Carbon::now();
        $tanggal   = $date->format('Y-m-d');
        $check = Presensi::where('nip', $this->pegawai()->nip)->where('tanggal', $tanggal)->first();
        if ($check == null) {
            return 'simpan';
        } else {
            return 'update';
        }
    }

    public function testing()
    {
        return view('pegawai.presensi.testing');
    }

    public function savephoto(Request $req)
    {
        $image = $req->image;  // your base64 encoded
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);

        // save to server (beware of permissions // set ke 775 atau 777)
        $namafoto = Carbon::now()->format('Ymd-His') . ".png";
        $result = file_put_contents('storage/' . $namafoto, base64_decode($image));
        return back();
    }

    public function storeLokasi(Request $req)
    {
        Auth::user()->pegawai->update(['lokasi_id' => $req->lokasi_id]);
        toastr()->success('Lokasi Sukses di Update');
        return back();
    }

    public function gantipassword()
    {
        return view('pegawai.gantipass');
    }

    public function updatepassword(Request $req)
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

    public function pagi()
    {
        $skpd = Skpd::find(Auth::user()->pegawai->skpd_id);
        if (Auth::user()->pegawai->lokasi == null) {
            $latlong2 = null;
        } else {
            $lokasi = Auth::user()->pegawai->lokasi;
            $lat        = (float)$skpd->lat;
            $long       = (float)$skpd->long;
            $radius     = (float)$skpd->radius;
            $latlong2 = [
                'lat' => $lokasi->lat,
                'lng' => $lokasi->long
            ];
        }

        $check = Presensi::where('nip', $this->pegawai()->nip)->where('tanggal', Carbon::today()->format('Y-m-d'))->first();
        if ($check == null) {
            $jam_masuk = '00:00:00';
            $jam_pulang = '00:00:00';
        } else {
            if ($check->shift == 'P') {
                $jam_masuk = $check->shift_jam_masuk;
                $jam_pulang = $check->shift_jam_pulang;
            } else {
                $jam_masuk = '00:00:00';
                $jam_pulang = '00:00:00';
            }
        }

        $agent = new Agent();
        $os = $agent->browser();

        $hari  = Carbon::now()->translatedFormat('l');

        $rentang = Rentang::where('hari', $hari)->first();
        if ($os == 'Safari') {
            toastr()->success('Pengguna Iphone Masih dalam pengembangan, harap gunakan android untuk sementara');
            return back();

            //return view('pegawai.presensi.radius.presensi', compact('skpd', 'latlong2', 'jam_masuk', 'jam_pulang', 'os', 'rentang'));
        } else {
            return view('pegawai.presensi.radius.pagi', compact('skpd', 'latlong2', 'jam_masuk', 'jam_pulang', 'rentang'));
        }
    }

    public function simpanpagi(Request $request)
    {
        $pegawai = Auth::user()->pegawai;
        $today = Carbon::now()->format('Y-m-d');
        $check = Presensi::where('nip', $pegawai->nip)->where('tanggal', $today)->first();
        if ($check == null) {

            $attr['nip'] = $pegawai->nip;
            $attr['nama'] = $pegawai->nama;
            $attr['tanggal'] = $today;
            $attr['jam_masuk'] = '00:00:00';
            $attr['jam_pulang'] = '00:00:00';
            $attr['skpd_id'] = $pegawai->skpd_id;
            $attr['shift_jam_masuk'] = Carbon::now()->format('Y-m-d H:i:s');
            $attr['jenis_presensi'] = 3;
            $attr['shift'] = 'P';
            $attr['puskesmas_id'] = $pegawai->puskesmas_id;

            Presensi::create($attr);
            toastr()->success('Presensi Masuk Berhasil');
            return back();
        } else {
            if ($check->shift == null) {
                $check->update([
                    'shift' => 'P',
                    'jenis_presensi' => 3,
                ]);
                toastr()->success('Update Menjadi Shift Pagi, silahkan absen lagi');
                return back();
            } else {
                if ($check->shift == 'P') {
                    if ($check->shift_jam_masuk == null) {
                        //simpan absen masuk
                        $check->update([
                            'shift_jam_masuk' => Carbon::now()->format('Y-m-d H:i:s'),
                        ]);
                        toastr()->success('Presensi Masuk Berhasil');
                        return back();
                    } else {
                        //simpan absen pulang
                        $check->update([
                            'shift_jam_pulang' => Carbon::now()->format('Y-m-d H:i:s'),
                        ]);
                        toastr()->success('Presensi Pulang Berhasil');
                        return back();
                    }
                } else {
                    if ($check->shift == 'S') {
                        $shift = 'Siang';
                    } else {
                        $shift = 'Malam';
                    }
                    toastr()->error('Anda tidak bisa absen karena hari ini anda sudah absen shift ' . $shift);
                    return back();
                }
            }
        }
    }
    public function siang()
    {
        $skpd = Skpd::find(Auth::user()->pegawai->skpd_id);
        if (Auth::user()->pegawai->lokasi == null) {
            $latlong2 = null;
        } else {
            $lokasi = Auth::user()->pegawai->lokasi;
            $lat        = (float)$skpd->lat;
            $long       = (float)$skpd->long;
            $radius     = (float)$skpd->radius;
            $latlong2 = [
                'lat' => $lokasi->lat,
                'lng' => $lokasi->long
            ];
        }

        $check = Presensi::where('nip', $this->pegawai()->nip)->where('tanggal', Carbon::today()->format('Y-m-d'))->first();
        if ($check == null) {
            $jam_masuk = '00:00:00';
            $jam_pulang = '00:00:00';
        } else {
            if ($check->shift == 'S') {
                $jam_masuk = $check->shift_jam_masuk;
                $jam_pulang = $check->shift_jam_pulang;
            } else {
                $jam_masuk = '00:00:00';
                $jam_pulang = '00:00:00';
            }
        }

        $agent = new Agent();
        $os = $agent->browser();

        $hari  = Carbon::now()->translatedFormat('l');

        $rentang = Rentang::where('hari', $hari)->first();
        if ($os == 'Safari') {
            toastr()->success('Pengguna Iphone Masih dalam pengembangan, harap gunakan android untuk sementara');
            return back();

            //return view('pegawai.presensi.radius.presensi', compact('skpd', 'latlong2', 'jam_masuk', 'jam_pulang', 'os', 'rentang'));
        } else {
            return view('pegawai.presensi.radius.siang', compact('skpd', 'latlong2', 'jam_masuk', 'jam_pulang', 'rentang'));
        }
    }

    public function simpansiang(Request $request)
    {
        $pegawai = Auth::user()->pegawai;
        $today = Carbon::now()->format('Y-m-d');
        $check = Presensi::where('nip', $pegawai->nip)->where('tanggal', $today)->first();
        if ($check == null) {

            $attr['nip'] = $pegawai->nip;
            $attr['nama'] = $pegawai->nama;
            $attr['tanggal'] = $today;
            $attr['jam_masuk'] = '00:00:00';
            $attr['jam_pulang'] = '00:00:00';
            $attr['skpd_id'] = $pegawai->skpd_id;
            $attr['shift_jam_masuk'] = Carbon::now()->format('Y-m-d H:i:s');
            $attr['jenis_presensi'] = 3;
            $attr['shift'] = 'S';
            $attr['puskesmas_id'] = $pegawai->puskesmas_id;

            Presensi::create($attr);
            toastr()->success('Presensi Masuk Berhasil');
            return back();
        } else {

            if ($check->shift == null) {
                $check->update([
                    'shift' => 'S',
                    'jenis_presensi' => 3,
                ]);
                toastr()->success('Update Menjadi Shift Siang, silahkan absen lagi');
                return back();
            } else {
                if ($check->shift == 'S') {
                    if ($check->shift_jam_masuk == null) {
                        //simpan absen masuk
                        $check->update([
                            'shift_jam_masuk' => Carbon::now()->format('Y-m-d H:i:s'),
                        ]);
                        toastr()->success('Presensi Masuk Berhasil');
                        return back();
                    } else {
                        //simpan absen pulang
                        $check->update([
                            'shift_jam_pulang' => Carbon::now()->format('Y-m-d H:i:s'),
                        ]);
                        toastr()->success('Presensi Pulang Berhasil');
                        return back();
                    }
                } else {
                    if ($check->shift == 'P') {
                        $shift = 'Pagi';
                    } else {
                        $shift = 'Malam';
                    }
                    toastr()->error('Anda tidak bisa absen karena hari ini anda sudah absen shift ' . $shift);
                    return back();
                }
            }
        }
    }

    public function malam()
    {
        $skpd = Skpd::find(Auth::user()->pegawai->skpd_id);
        $data = Presensi::where('nip', $this->pegawai()->nip)->whereMonth('tanggal', Carbon::now()->format('m'))->get();

        if (Auth::user()->pegawai->lokasi == null) {
            $latlong2 = null;
        } else {
            $lokasi = Auth::user()->pegawai->lokasi;
            $lat        = (float)$skpd->lat;
            $long       = (float)$skpd->long;
            $radius     = (float)$skpd->radius;
            $latlong2 = [
                'lat' => $lokasi->lat,
                'lng' => $lokasi->long
            ];
        }

        $check = Presensi::where('nip', $this->pegawai()->nip)->where('tanggal', Carbon::today()->format('Y-m-d'))->first();
        if ($check == null) {
            $jam_masuk = '00:00:00';
            $jam_pulang = '00:00:00';
        } else {
            if ($check->shift == 'M') {
                $jam_masuk = $check->shift_jam_masuk;
                $jam_pulang = $check->shift_jam_pulang;
            } else {
                $jam_masuk = '00:00:00';
                $jam_pulang = '00:00:00';
            }
        }

        $agent = new Agent();
        $os = $agent->browser();

        $hari  = Carbon::now()->translatedFormat('l');

        $rentang = Rentang::where('hari', $hari)->first();
        if ($os == 'Safari') {
            toastr()->success('Pengguna Iphone Masih dalam pengembangan, harap gunakan android untuk sementara');
            return back();

            //return view('pegawai.presensi.radius.presensi', compact('skpd', 'latlong2', 'jam_masuk', 'jam_pulang', 'os', 'rentang'));
        } else {
            return view('pegawai.presensi.radius.malam', compact('skpd', 'latlong2', 'jam_masuk', 'jam_pulang', 'rentang', 'data'));
        }
    }

    public function simpanmalam(Request $request)
    {
        $pegawai = Auth::user()->pegawai;
        $today = Carbon::now()->format('Y-m-d');
        $check = Presensi::where('nip', $pegawai->nip)->where('tanggal', $today)->first();
        if ($check == null) {

            $attr['nip'] = $pegawai->nip;
            $attr['nama'] = $pegawai->nama;
            $attr['tanggal'] = $today;
            $attr['jam_masuk'] = '00:00:00';
            $attr['jam_pulang'] = '00:00:00';
            $attr['skpd_id'] = $pegawai->skpd_id;
            $attr['shift_jam_masuk'] = Carbon::now()->format('Y-m-d H:i:s');
            $attr['jenis_presensi'] = 3;
            $attr['shift'] = 'M';
            $attr['puskesmas_id'] = $pegawai->puskesmas_id;

            Presensi::create($attr);
            toastr()->success('Presensi Masuk Berhasil');
            return back();
        } else {

            if ($check->shift == null) {
                $check->update([
                    'shift' => 'M',
                    'jenis_presensi' => 3,
                ]);
                toastr()->success('Update Menjadi Shift Malam, silahkan absen lagi');
                return back();
            } else {

                if ($check->shift == 'M') {
                    if ($check->shift_jam_masuk == null) {
                        //simpan absen masuk
                        $check->update([
                            'shift_jam_masuk' => Carbon::now()->format('Y-m-d H:i:s'),
                        ]);
                        toastr()->success('Presensi Masuk Berhasil');
                        return back();
                    } else {
                        //simpan absen pulang
                        $check->update([
                            'shift_jam_pulang' => Carbon::now()->format('Y-m-d H:i:s'),
                        ]);
                        toastr()->success('Presensi Pulang Berhasil');
                        return back();
                    }
                } else {
                    if ($check->shift == 'P') {
                        $shift = 'Pagi';
                    } else {
                        $shift = 'Siang';
                    }
                    toastr()->error('Anda tidak bisa absen karena hari ini anda sudah absen shift ' . $shift);
                    return back();
                }
            }
        }
    }

    public function malam_pulang($id)
    {
        $presensi = Presensi::find($id);
        $today = Carbon::now()->format('Y-m-d');
        if ($presensi->shift == 'M' || $presensi->shift == null) {
            if (Carbon::parse($presensi->tanggal)->diffInDays($today) > 1) {
                toastr()->error('Presensi Pulang Tidak bisa dilakukan karena sudah 2 hari');
                return back();
            } elseif ($presensi->tanggal == $today) {
                toastr()->error('Presensi Pulang Shift Malam Hanya bisa di lakukan pada tanggal berikutnya');
                return back();
            } else {
                $presensi->update([
                    'shift_jam_pulang' => Carbon::now()->format('Y-m-d H:i:s'),
                    'shift' => 'M',
                ]);
                toastr()->success('Presensi Pulang Berhasil');
                return back();
            }
        } else {
            toastr()->error('Tidak bisa absen, karena anda sudah absen shift pagi/siang');
            return back();
        }
    }

    public function malam_masuk($id)
    {
        Presensi::find($id)->update([
            'shift_jam_masuk' => Carbon::now()->format('Y-m-d H:i:s'),
            'shift' => 'M',
        ]);
        toastr()->success('Presensi Masuk Berhasil');
        return back();
    }
}
