<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function pegawai()
    {
        return view('pegawai.home');
    }
}
