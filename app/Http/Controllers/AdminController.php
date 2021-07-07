<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function updatelocation(Request $req)
    {
        Auth::user()->skpd->update([
            'lat' => $req->lat,
            'long' => $req->long,
            'radius' => $req->radius,
        ]);

        return back();
    }
}
