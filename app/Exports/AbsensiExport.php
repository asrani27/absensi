<?php

namespace App\Exports;

use App\Models\Pegawai;
use App\Models\Presensi;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AbsensiExport implements FromView, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $tanggal;

    public function __construct($tanggal)
    {
        $this->tanggal = $tanggal;
    }

    public function view(): View
    {
        $tanggal = $this->tanggal;
        $presensi = Presensi::where('tanggal', $this->tanggal)->where('skpd_id', Auth::user()->skpd->id)->orderBy('skpd_id', 'ASC')->orderBy('puskesmas_id', 'ASC')->get();
        return view('exports.presensi', compact('presensi', 'tanggal'));
    }
}
