<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function pegawai()
    {
        return view('pegawai.home');
    }

    public function admin()
    {
        $user    = Auth::user()->skpd;
        $lat     = (float)$user->lat;
        $long    = (float)$user->long;
        $latlong = [
            'lat' => $lat,
            'lng' => $long
        ];
        
        return view('admin.home',compact('latlong','lat','long'));
    }
}
