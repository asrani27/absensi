<?php

namespace App\Http\Controllers;

use App\Models\Qr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrcodeController extends Controller
{
    public function index()
    {
        $data = Qr::orderBy('id','DESC')->get();
        return view('admin.qrcode.index',compact('data'));
    }

    public function generateQrcode()
    {
        $key = Auth::user()->skpd->kode_skpd.Carbon::today()->format('d-m-Y');
        
        QrCode::size(500)
            ->format('png')
            ->generate('asrandev.com', public_path('public/qr/qrcode.png'));
        return back();
    }

    public function create()
    {
        return view('admin.qrcode.create');
    }
    
    public function store(Request $request)
    {
        
    }
    
    public function show($id)
    {
        //
    }
    
    public function edit($id)
    {
        
    }
    
    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {
        
    }
}
