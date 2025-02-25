<?php

namespace App\Exports;

use App\Models\Pegawai;
use App\Models\Presensi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class AbsensiExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $presensi = Pegawai::get();
        return view('exports.presensi', compact('presensi'));
    }
}
