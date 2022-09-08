<?php

namespace App\Http\Controllers;

use App\Models\Role;
use GuzzleHttp\Client;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // public function user()
    // {
    //     $user = Auth::user();
    //     $user['name'] = $user->pegawai->nama;

    //     return $user;
    // }

    public function login(Request $req)
    {
        if (Auth::attempt(['username' => $req->username, 'password' => $req->password], true)) {

            if (Auth::user()->hasRole('pegawai')) {
                if (Auth::user()->pegawai->is_aktif == 0) {
                    Auth::logout();
                    toastr()->error('Anda Telah NonAktif');
                    return redirect('/');
                } else {
                    return redirect('/home/pegawai');
                }
            } elseif (Auth::user()->hasRole('admin')) {
                return redirect('/home/admin');
            } elseif (Auth::user()->hasRole('superadmin')) {
                return redirect('/home/superadmin');
            } elseif (Auth::user()->hasRole('puskesmas')) {
                return redirect('/home/puskesmas');
            }
            //  else {
            //     $role = Role::where('name', 'pegawai')->first();
            //     $u = Auth::user();
            //     $u->roles()->attach($role);
            //     return redirect('/home/pegawai');
            // }
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
