<?php

namespace App\Exports;

use App\Models\Pegawai;
use App\Models\Presensi;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
//use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PresensiExport implements FromCollection, WithEvents, WithColumnFormatting, WithStyles, WithHeadings, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $tanggal;
    private $skpd;

    public function __construct($tanggal, $skpd)
    {
        $this->tanggal = $tanggal;
        $this->skpd = $skpd;
    }

    public function collection()
    {

        $presensi = Presensi::where('skpd_id', $this->skpd->id)->where('tanggal', $this->tanggal)->get();
        $datapegawai = Pegawai::where('skpd_id', $this->skpd->id)->where('jabatan', '!=', null)->orderBy('urutan', 'DESC')->get();
        $tanggal = $this->tanggal;
        //mapping data
        $data = $datapegawai->map(function ($item, $value) use ($presensi, $tanggal) {
            $check = $presensi->where('nip', $item->nip);
            if (count($check) == 1) {
                $item->presensi = $check->first();
            } elseif (count($check) == 0) {
                //Buat Presensi Default
                $p = new Presensi;
                $p->nip = $item->nip;
                $p->nama = $item->nama;
                $p->skpd_id = $item->skpd_id;
                $p->tanggal = $tanggal;
                $p->jam_masuk = '00:00:00';
                $p->jam_pulang = '00:00:00';
                $p->save();
            } else {
                //Log Data Double 
                $d = new DoubleData;
                $d->nip = $item->nip;
                $d->tanggal = $tanggal;
                $d->save();
            }
            $item->nomor = $value + 1;

            return $item->only(['nomor', 'nama']);
        })->values();

        return $data;
    }
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function headings(): array
    {
        return [
            ['DAFTAR HADIR PEGAWAI NEGERI SIPIL ' . strtoupper($this->skpd->nama) . ' KOTA BANJARMASIN'],
            [],
            ['NO', 'NIP/NAMA']
        ];
    }

    public function registerEvents(): array
    {
        $styleArrayData = [
            'font' => [
                'size' => 18,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                ],
            ],
        ];

        return [

            AfterSheet::class => function (AfterSheet $event) use ($styleArrayData) {
                $event->sheet->getStyle('A1:W25')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);
                // $event->sheet->getStyle('A2')->applyFromArray($styleArrayData);
                // $event->sheet->getDelegate()->mergeCells('A2:E2');
            }
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->mergeCells('A1:E1');
    }
}
