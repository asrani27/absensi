<?php

namespace App\Exports;

use App\Models\Pegawai;
use App\Models\Presensi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AbsensiExport implements FromView, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $presensi = Presensi::where('tanggal', '2025-02-24')->orderBy('skpd_id', 'ASC')->orderBy('puskesmas_id', 'ASC')->get();
        return view('exports.presensi', compact('presensi'));
    }
}
