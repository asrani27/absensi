<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $req)
    {
        if (Auth::attempt(['username' => $req->username, 'password' => $req->password])) {
            if(Auth::user()->hasRole('pegawai')){
                return redirect('/home/pegawai');
            }elseif(Auth::user()->hasRole('admin')){
                return redirect('/home/admin');
            }elseif(Auth::user()->hasRole('superadmin')){
                return redirect('/home/superadmin');
            }
        } else {
            toastr()->error('Username / Password Tidak Ditemukan');
            $req->flash();
            return back();
        }

        // $client = new Client(['base_uri' => 'https://tpp.banjarmasinkota.go.id/api/']);
        // $options = [
        //     'form_params' => [
        //         "username" => $req->username,
        //         "password" => $req->password,
        //        ]
        //    ]; 
        // $response = $client->request('POST', 'login',$options);
        // return $response->getBody();
    }
}
