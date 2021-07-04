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
            return redirect('/home/pegawai');
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
