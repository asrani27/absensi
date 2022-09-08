<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    public function gantipass(Request $req)
    {
        if (!Hash::check($req->password_lama, Auth::user()->password)) {
            $data['message_error'] = 201;
            $data['message']       = 'password lama tidak sesuai';
            $data['data']          = null;
            return response()->json($data);
        }
        if ($req->password_baru != $req->confirm_password_baru) {
            $data['message_error'] = 201;
            $data['message']       = 'konfirmasi password tidak sama';
            $data['data']          = null;
            return response()->json($data);
        } else {
            Auth::user()->update([
                'password' => bcrypt($req->password_baru),
            ]);
            $data['message_error'] = 200;
            $data['message']       = 'Berhasil Di ubah';
            $data['data']          = null;
            return response()->json($data);
        }
    }

    public function login(Request $req)
    {
        if ($req->username == null || $req->password == null) {
            $data['message_error'] = 101;
            $data['message']       = 'username atau password kosong';
            $data['data']          = null;
            return response()->json($data);
        } else {
            if (Auth::attempt(['username' => $req->username, 'password' => $req->password])) {

                $user = Auth::user();
                if ($user->tokens()->first() == null) {
                    $token = $user->createToken('myapptoken')->plainTextToken;
                } else {
                    $user->tokens()->delete();
                    $token = $user->createToken('myapptoken')->plainTextToken;
                }

                if ($user->android_id == null) {
                    //check device digunakan oleh nip lain
                    $checkDevice = User::where('android_id', $req->android_id)->first();
                    if ($checkDevice == null) {
                        $user->update([
                            'android_id' => $req->android_id,
                            'device_info' => $req->device_info,
                        ]);

                        $data['message_error'] = 200;
                        $data['message']       = 'Data Ditemukan';
                        $data['data']          = Auth::user()->pegawai;
                        $data['api_token']     = $token;
                        return response()->json($data);
                    } else {
                        $data['message_error'] = 201;
                        $data['message']       = 'Device Ini telah di gunakan oleh ' . $checkDevice->name;
                        $data['data']          = null;
                        return response()->json($data);
                    }
                } else {
                    if ($user->username == $req->username && $user->android_id == $req->android_id) {
                        $data['message_error'] = 200;
                        $data['message']       = 'Data Ditemukan';
                        $data['data']          = Auth::user()->pegawai;
                        $data['api_token']     = $token;
                        return response()->json($data);
                    } else {
                        $data['message_error'] = 201;
                        $data['message']       = 'Presensi hanya boleh single device, 1 nip 1 device, silahkan hub admin untuk reset device';
                        $data['data']          = null;
                        return response()->json($data);
                    }
                }
            } else {
                $data['message_error'] = 201;
                $data['message']       = 'username atau password anda tidak ditemukan';
                $data['data']          = null;
                return response()->json($data);
            }
        }
    }

    public function user()
    {
        $user = Auth::user();
        $user['name'] = $user->pegawai->nama;
        return $user;
    }
    public function testing()
    {
        return response()->json('testing');
    }
}
