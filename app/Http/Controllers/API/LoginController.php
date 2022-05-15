<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
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
                    $user->update([
                        'android_id' => $req->android_id,
                        'device_info' => $req->device_info,
                    ]);

                    $data['message_error'] = 200;
                    $data['message']       = 'Data Ditemukan';
                    $data['data']          = Auth::user()->pegawai;
                    $data['api_token']     = $token;
                } else {
                    if ($user->where('username', $req->username) && $user->where('android_id', $req->android_id)) {
                        $data['message_error'] = 200;
                        $data['message']       = 'Data Ditemukan';
                        $data['data']          = Auth::user()->pegawai;
                        $data['api_token']     = $token;
                    } else {
                        $user->where('android_id', $req->android_id)->first();
                        $data['message_error'] = 201;
                        $data['message']       = 'Device Ini Telah Di gunakan oleh ' . $user->name;
                        $data['data']          = null;
                    }
                }
                return response()->json($data);
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
        return Auth::user();
    }
}
