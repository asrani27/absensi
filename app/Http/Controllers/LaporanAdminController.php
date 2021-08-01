<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanAdminController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }
}
