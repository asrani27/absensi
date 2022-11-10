<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Pegawai;
use App\Models\Presensi;
use App\Models\DetailCuti;
use App\Models\LiburNasional;
use Illuminate\Console\Command;

class generateTU extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generatetu  {--bulan=} {--tahun=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate presensi TU';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->option('bulan') != null) {
            $month = $this->option('bulan');
            $year = $this->option('tahun');
        } else {
            $month = Carbon::now()->month;
            $year = Carbon::now()->year;
        }

        $pegawai = Pegawai::where('sekolah_id', '!=', null)->where('nip', '!=', '196911262001031001')->where('nip', '!=', '196502131988041002')->get();
        foreach ($pegawai as $p) {
            $presensi = Presensi::where('nip', $p->nip)->whereMonth('tanggal', $month)->whereYear('tanggal', $year)->get();
            foreach ($presensi as $pre) {
                if (Carbon::parse($pre->tanggal)->translatedFormat('l') == 'Minggu' || LiburNasional::where('tanggal', $pre->tanggal)->first() != null) {
                    $pre->update([
                        'jam_masuk' => $pre->tanggal . ' ' . '00:00:00',
                        'jam_pulang' => $pre->tanggal . ' ' . '00:00:00',
                        'terlambat' => 0,
                        'lebih_awal' => 0,
                    ]);
                } elseif (DetailCuti::where('nip', $pre->nip)->where('tanggal', $pre->tanggal)->first() != null) {
                    $pre->update([
                        'jenis_keterangan_id' => DetailCuti::where('nip', $pre->nip)->where('tanggal', $pre->tanggal)->first()->jenis_keterangan_id,
                        'terlambat' => 0,
                        'lebih_awal' => 0,
                    ]);
                } else {
                    if ($p->jenis_presensi == 1 && Carbon::parse($pre->tanggal)->translatedFormat('l') == 'Sabtu') {
                        $pre->update([
                            'jam_masuk' => $pre->tanggal . ' ' . '00:00:00',
                            'jam_pulang' => $pre->tanggal . ' ' . '00:00:00',
                            'terlambat' => 0,
                            'lebih_awal' => 0,
                        ]);
                    } else {
                        $a = '07';
                        $b = rand(10, 29);
                        $c = rand(10, 59);
                        $masuk = $pre->tanggal . ' ' . $a . ':' . $b . ':' . $c;
                        $d = '16';
                        $e = rand(10, 29);
                        $f = rand(10, 59);
                        $pulang = $pre->tanggal . ' ' . $d . ':' . $e . ':' . $f;
                        $pre->update([
                            'jam_masuk' => $masuk,
                            'jam_pulang' => $pulang,
                            'terlambat' => 0,
                            'lebih_awal' => 0,
                        ]);
                    }
                }
            }
        }
    }
}
