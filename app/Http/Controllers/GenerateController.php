<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\Pegawai;
use App\Models\Presensi;
use Carbon\CarbonPeriod;
use App\Jobs\NotNullProcess;
use Illuminate\Http\Request;
use App\Jobs\SyncPegawaiAdmin;

class GenerateController extends Controller
{
    public function generate($bulan)
    {
        $year   = Carbon::today()->format('Y');
        $month  = $year.'-'.$bulan;

        $start  = Carbon::parse($month)->startOfMonth();
        $end    = Carbon::parse($month)->endOfMonth();
        $period = CarbonPeriod::create($start, $end);

        $pegawai = Pegawai::get();

        foreach($period as $date)
        {
            $dates[] = $date->format('Y-m-d');
        }

        $pegawai->map(function($item)use($dates){
            foreach($dates as $d)
            {
                $check = Presensi::where('nip', $item->nip)->where('tanggal', $d)->first();
                if($check == null){
                    $p = new Presensi;
                    $p->nip = $item->nip;
                    $p->tanggal = $d;
                    $p->save();
                }else{

                }
            }
            return $item;
        });
        toastr()->success('Berhasil Di Generate');
        return back();
    }
    
    public function index()
    {
        return view('superadmin.generate.tanggal');
    }

    public function notnull()
    {
        $data = Presensi::where('jam_masuk', null)->orWhere('jam_pulang', null)->take(1000)->get();
        
        foreach($data as $item){
            NotNullProcess::dispatch($item);
        }
        
        toastr()->success('Berhasil Di Generate');
        return back();
    }

    public function tarikpegawai()
    {
        $client = new Client(['base_uri' => 'https://tpp.banjarmasinkota.go.id/api/']);
        $response = $client->request('get', 'pegawai', ['verify' => false]);
        $data =  json_decode($response->getBody())->data;

        foreach($data as $item)
        {
            SyncPegawaiAdmin::dispatch($item);
        }

        toastr()->success('Berhasil Di Generate');
        return back();
    }
}
