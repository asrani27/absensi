<?php

namespace App\Exports;

use App\Models\Pegawai;
use App\Models\Ringkasan;
use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromCollection;

class laporan2023 implements FromView, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        // Ambil semua pegawai yang aktif
        $pegawai = Pegawai::where('status_asn', null)->get();

        // Ambil data ringkasan yang relevan sekaligus untuk semua pegawai pada tahun 2023 dan seluruh bulan (Januari - Desember)
        $ringkasan = Ringkasan::whereIn('nip', $pegawai->pluck('nip'))
            ->where('tahun', '2023')
            ->whereIn('bulan', ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'])  // Menyaring untuk bulan 01 sampai 12
            ->get()
            ->keyBy(function ($item) {
                return $item->nip . '-' . $item->bulan;  // Gabungkan nip dan bulan sebagai key untuk memudahkan pencarian
            });

        // Map data pegawai dan sesuaikan dengan data ringkasan untuk setiap bulan
        $data = $pegawai->map(function ($item) use ($ringkasan) {
            // Iterasi untuk setiap bulan dari Januari sampai Desember
            foreach (range(1, 12) as $month) {
                $month = str_pad($month, 2, '0', STR_PAD_LEFT);  // Pastikan format bulan dua digit (01, 02, ...)
                $ringkasanData = $ringkasan->get($item->nip . '-' . $month);

                // Jika data ringkasan tidak ditemukan untuk bulan tersebut, set nilai bulan ke 0
                $item->{'bulan_' . $month} = $ringkasanData ? $ringkasanData->persen_kehadiran : 0;
            }

            return $item;
        });


        return view('superadmin.exports.laporan2024', compact('data'));
    }
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                // Mendapatkan worksheet
                $sheet = $event->sheet->getDelegate();

                // Mengatur autosize untuk kolom A, B, C, dan D
                foreach (['A', 'B', 'C', 'D'] as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
