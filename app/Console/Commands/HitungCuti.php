<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Cuti;
use App\Models\Komando;
use App\Models\Presensi;
use Carbon\CarbonPeriod;
use App\Models\LiburNasional;
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
        $tanggal = Carbon::now()->format('Y-m-d');
        $data = Cuti::whereIn('jenis_keterangan_id', [1, 4, 5, 7, 9, 3, 6, 8])->whereDate('created_at', '=', '2022-02-21')->get();

        foreach ($data as $item) {
            $period = CarbonPeriod::create($item->tanggal_mulai, $item->tanggal_selesai);
            foreach ($period as $date) {
                if ($date->translatedFormat('l') == 'Minggu') {
                } else {
                    if (LiburNasional::where('tanggal', $date->format('Y-m-d'))->first() == null) {
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
                            $p->jenis_keterangan_id = $item->jenis_keterangan_id;
                            $p->save();
                        } else {
                            $check->update([
                                'jam_masuk' => '00:00:00',
                                'jam_pulang' => '00:00:00',
                                'terlambat' => 0,
                                'lebih_awal' => 0,
                                'jenis_keterangan_id' => $item->jenis_keterangan_id,
                            ]);
                        }
                    } else {
                    }
                }
            }
        }

        $com['nama_command'] = 'hitung cuti hari ini';
        $com['waktu_eksekusi'] = Carbon::now()->format('Y-m-d H:i:s');

        Komando::create($com);
    }
}
