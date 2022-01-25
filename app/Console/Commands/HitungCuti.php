<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Cuti;
use App\Models\Komando;
use App\Models\Presensi;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;

class HitungCuti extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hitungcuti';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate cuti bulan ini';

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
        $tahun = Carbon::now()->format('Y');
        $data = Cuti::where('jenis_keterangan_id', 7)->whereYear('tanggal_mulai', $tahun)->get();

        foreach ($data as $item) {
            $period = CarbonPeriod::create($item->tanggal_mulai, $item->tanggal_selesai);
            foreach ($period as $date) {
                //simpan cuti tahun di presensi
                $check = Presensi::where('nip', $item->nip)->where('tanggal', $date->format('Y-m-d'))->first();
                if ($check == null) {
                    //save
                    $p = new Presensi;
                    $p->nip = $item->nip;
                    $p->nama = $item->nama;
                    $p->skpd_id = $item->skpd_id;
                    $p->tanggal = $date->format('Y-m-d');
                    $p->jam_masuk = '00:00:00';
                    $p->jam_pulang = '00:00:00';
                    $p->terlambat = 0;
                    $p->lebih_awal = 0;
                    $p->jenis_keterangan_id = null;
                    $p->save();
                } else {
                    $check->update([
                        'jam_masuk' => '00:00:00',
                        'jam_pulang' => '00:00:00',
                        'terlambat' => 0,
                        'lebih_awal' => 0,
                        'jenis_keterangan_id' => null,
                    ]);
                }
            }
        }

        $com['nama_command'] = 'hitung cuti bulan ini';
        $com['waktu_eksekusi'] = Carbon::now()->format('Y-m-d H:i:s');

        Komando::create($com);
    }
}
