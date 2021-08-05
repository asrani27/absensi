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
        if($req->username == null || $req->password == null){
            $data['message_error'] = 101;
            $data['message']       = 'username atau password kosong';
            $data['data']          = null;
            return response()->json($data);
        }else{
            if (Auth::attempt(['username' => $req->username, 'password' => $req->password])) {
                Auth::user()->update([
                    'api_token' => Hash::make(Str::random(100))
                ]);

                $data['message_error'] = 200;
                $data['message']       = 'Data Ditemukan';
                $data['data']          = Auth::user()->pegawai;
                $data['api_token']     = Auth::user()->api_token;
                return response()->json($data);
                
            } else {
                $data['message_error'] = 201;
                $data['message']       = 'username atau password anda tidak ditemukan';
                $data['data']          = null;
                return response()->json($data);
            }
        } 
    }
}
