<?php

namespace App\Http\Controllers;

use App\Models\Qr;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrcodeController extends Controller
{
    public function index()
    {
        $data = Qr::orderBy('id','DESC')->paginate(10);
        return view('admin.qrcode.index',compact('data'));
    }

    public function generateQrcode()
    {        
        $year   = Carbon::today()->format('Y-m');

        $start  = Carbon::parse($year)->startOfMonth();
        $end    = Carbon::parse($year)->endOfMonth();
        $period = CarbonPeriod::create($start, $end);
        
        foreach($period as $date)
        {
            $dates[] = $date->format('Y-m-d');
        }

        foreach($dates as $d)
        {
            $check = Qr::where('tanggal', $d)->where('skpd_id', Auth::user()->skpd->id)->first();
            if($check == null){
                $q = new Qr;
                $q->tanggal = $d;
                $q->qrcode = Auth::user()->skpd->kode_skpd.$d;
                $q->skpd_id = Auth::user()->skpd->id;
                $q->save();
            }else{

            }
        }
        toastr()->success('Berhasil Di Generate');
        return back();
    }

    public function tampilQr($id)
    {
        $data = Qr::find($id)->qrcode;
        return view('admin.qrcode.tampil',compact('data'));
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
