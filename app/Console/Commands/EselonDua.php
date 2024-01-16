<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Loss;
use App\Models\Pegawai;
use App\Models\Presensi;
use App\Models\DetailCuti;
use App\Models\LiburNasional;
use Illuminate\Console\Command;

class EselonDua extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eselondua {--tanggal=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate presensi eselon dua';

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
        if ($this->option('tanggal') != null) {
            $tanggal = $this->option('tanggal');
        } else {
            $tanggal = Carbon::now()->format('Y-m-d');
        }

        $eselondua = Loss::get()->pluck('nip');
        $pegawai = Pegawai::whereIn('nip', $eselondua)->get();

        //dd($pegawai, $tanggal);
        foreach ($pegawai as $p) {
            $presensi = Presensi::where('nip', $p->nip)->where('tanggal', $tanggal)->get();
            foreach ($presensi as $pre) {

                // 0 kan jika hari sabtu, minggu dan libur nasional
                if (Carbon::parse($pre->tanggal)->translatedFormat('l') == 'Sabtu' || Carbon::parse($pre->tanggal)->translatedFormat('l') == 'Minggu' || LiburNasional::where('tanggal', $pre->tanggal)->first() != null) {
                    $pre->update([
                        'jam_masuk' => $pre->tanggal . ' ' . '00:00:00',
                        'jam_pulang' => $pre->tanggal . ' ' . '00:00:00',
                        'terlambat' => 0,
                        'lebih_awal' => 0,
                    ]);
                }

                //jika cuti
                elseif (DetailCuti::where('nip', $pre->nip)->where('tanggal', $pre->tanggal)->first() != null) {
                    $pre->update([
                        'jenis_keterangan_id' => DetailCuti::where('nip', $pre->nip)->where('tanggal', $pre->tanggal)->first()->jenis_keterangan_id,
                        'terlambat' => 0,
                        'lebih_awal' => 0,
                    ]);
                } else {
                    if (Carbon::parse($pre->tanggal)->translatedFormat('l') == 'Jumat') {
                        $a = '07';
                        $b = rand(10, 29);
                        $c = rand(10, 59);
                        $masuk = $pre->tanggal . ' ' . $a . ':' . $b . ':' . $c;
                        $d = '11';
                        $e = rand(10, 29);
                        $f = rand(10, 59);
                        $pulang = $pre->tanggal . ' ' . $d . ':' . $e . ':' . $f;
                        $pre->update([
                            'jam_masuk' => $masuk,
                            'jam_pulang' => $pulang,
                            'terlambat' => 0,
                            'lebih_awal' => 0,
                        ]);
                    } else {
                        $a = '07';
                        $b = rand(10, 29);
                        $c = rand(10, 59);
                        $masuk = $pre->tanggal . ' ' . $a . ':' . $b . ':' . $c;
                        $d = '17';
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
