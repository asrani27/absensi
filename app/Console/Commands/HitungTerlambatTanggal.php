<?php

namespace App\Console\Commands;

use App\Models\ErrorData;
use App\Models\Jam6;
use App\Models\Jam6Ramadhan;
use App\Models\Jam;
use App\Models\JamRamadhan;
use App\Models\LiburNasional;
use App\Models\Pegawai;
use App\Models\Presensi;
use App\Models\Ramadhan;
use Carbon\Carbon;
use Illuminate\Console\Command;

class HitungTerlambatTanggal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'potongan-terlambat {--tanggal=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'hitung potongan terlambat berdasarkan tanggal yang dipilih';

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
     * Hitung terlambat untuk non-Ramadhan
     *
     * @param  \App\Models\Presensi  $presensi
     * @param  \App\Models\Jam|\App\Models\Jam6  $jam
     * @return void
     */
    private function hitungTerlambat($presensi, $jam)
    {
        if (Pegawai::where('nip', $presensi->nip)->first() == null) {
            return;
        }

        // Calculate late arrival (terlambat)
        if ($presensi->jam_masuk == null || Carbon::parse($presensi->jam_masuk)->format('H:i:s') == '00:00:00') {
            if (Pegawai::where('nip', $presensi->nip)->first()->jenis_presensi == 1) {
                if (Carbon::parse($presensi->tanggal)->translatedFormat('l') == 'Jumat') {
                    $presensi->update([
                        'terlambat' => 105,
                        'denda_terlambat' => 1.5,
                    ]);
                } else {
                    $presensi->update([
                        'terlambat' => 255,
                        'denda_terlambat' => 1.5,
                    ]);
                }
            } elseif (Pegawai::where('nip', $presensi->nip)->first()->jenis_presensi == 2) {
                if (Carbon::parse($presensi->tanggal)->translatedFormat('l') == 'Jumat') {
                    $presensi->update([
                        'terlambat' => 105,
                        'denda_terlambat' => 1.5,
                    ]);
                } elseif (Carbon::parse($presensi->tanggal)->translatedFormat('l') == 'Sabtu') {
                    $presensi->update([
                        'terlambat' => 180,
                        'denda_terlambat' => 1.5,
                    ]);
                } else {
                    $presensi->update([
                        'terlambat' => 210,
                        'denda_terlambat' => 1.5,
                    ]);
                }
            }
        } elseif ($presensi->jam_masuk > $presensi->tanggal . ' ' . $jam->jam_masuk) {
            $terlambat = floor(Carbon::parse($presensi->jam_masuk)->diffInSeconds($presensi->tanggal . ' ' . $jam->jam_masuk) / 60);

            if ($terlambat >= 1 && $terlambat <= 30) {
                $denda_terlambat = 0.5;
            } elseif ($terlambat >= 31 && $terlambat <= 60) {
                $denda_terlambat = 1;
            } elseif ($terlambat >= 61 && $terlambat <= 90) {
                $denda_terlambat = 1.25;
            } elseif ($terlambat >= 91) {
                $denda_terlambat = 1.50;
            } else {
                $denda_terlambat = 0;
            }
            $presensi->update([
                'terlambat' => $terlambat,
                'denda_terlambat' => $denda_terlambat,
            ]);
        } else {
            $presensi->update([
                'terlambat' => 0,
                'denda_terlambat' => 0,
            ]);
        }

        // Calculate early departure (lebih_awal)
        if ($presensi->jam_pulang == null || Carbon::parse($presensi->jam_pulang)->format('H:i:s') == '00:00:00') {
            if (Pegawai::where('nip', $presensi->nip)->first()->jenis_presensi == 1) {
                if (Carbon::parse($presensi->tanggal)->translatedFormat('l') == 'Jumat') {
                    $presensi->update([
                        'lebih_awal' => 105,
                        'denda_lebih_awal' => 1.5,
                    ]);
                } else {
                    $presensi->update([
                        'lebih_awal' => 255,
                        'denda_lebih_awal' => 1.5,
                    ]);
                }
            } elseif (Pegawai::where('nip', $presensi->nip)->first()->jenis_presensi == 2) {
                if (Carbon::parse($presensi->tanggal)->translatedFormat('l') == 'Jumat') {
                    $presensi->update([
                        'lebih_awal' => 105,
                        'denda_lebih_awal' => 1.5,
                    ]);
                } elseif (Carbon::parse($presensi->tanggal)->translatedFormat('l') == 'Sabtu') {
                    $presensi->update([
                        'lebih_awal' => 180,
                        'denda_lebih_awal' => 1.5,
                    ]);
                } else {
                    $presensi->update([
                        'lebih_awal' => 210,
                        'denda_lebih_awal' => 1.5,
                    ]);
                }
            }
        } elseif ($presensi->jam_pulang < $presensi->tanggal . ' ' . $jam->jam_pulang) {
            $lebih_awal = floor(Carbon::parse($presensi->jam_pulang)->diffInSeconds($presensi->tanggal . ' ' . $jam->jam_pulang) / 60);
            if ($lebih_awal >= 1 && $lebih_awal <= 30) {
                $denda_lebih_awal = 0.5;
            } elseif ($lebih_awal >= 31 && $lebih_awal <= 60) {
                $denda_lebih_awal = 1;
            } elseif ($lebih_awal >= 61 && $lebih_awal <= 90) {
                $denda_lebih_awal = 1.25;
            } elseif ($lebih_awal >= 91) {
                $denda_lebih_awal = 1.50;
            } else {
                $denda_lebih_awal = 0;
            }
            $presensi->update([
                'lebih_awal' => $lebih_awal,
                'denda_lebih_awal' => $denda_lebih_awal,
            ]);
        } else {
            $presensi->update([
                'lebih_awal' => 0,
                'denda_lebih_awal' => 0,
            ]);
        }
    }

    /**
     * Hitung terlambat untuk Ramadhan
     *
     * @param  \App\Models\Presensi  $presensi
     * @param  \App\Models\JamRamadhan|\App\Models\Jam6Ramadhan  $jam
     * @return void
     */
    private function hitungTerlambatRamadhan($presensi, $jam)
    {
        if (Pegawai::where('nip', $presensi->nip)->first() == null) {
            return;
        }

        // Calculate late arrival (terlambat)
        if ($presensi->jam_masuk == null || Carbon::parse($presensi->jam_masuk)->format('H:i:s') == '00:00:00') {
            if (Pegawai::where('nip', $presensi->nip)->first()->jenis_presensi == 1) {
                if (Carbon::parse($presensi->tanggal)->translatedFormat('l') == 'Jumat') {
                    $presensi->update([
                        'terlambat' => 75,
                        'denda_terlambat' => 1.25,
                    ]);
                } else {
                    $presensi->update([
                        'terlambat' => 240,
                        'denda_terlambat' => 1.50,
                    ]);
                }
            } elseif (Pegawai::where('nip', $presensi->nip)->first()->jenis_presensi == 2) {
                if (Carbon::parse($presensi->tanggal)->translatedFormat('l') == 'Jumat') {
                    $presensi->update([
                        'terlambat' => 90,
                        'denda_terlambat' => 1.25,
                    ]);
                } elseif (Carbon::parse($presensi->tanggal)->translatedFormat('l') == 'Sabtu') {
                    $presensi->update([
                        'terlambat' => 165,
                        'denda_terlambat' => 1.5,
                    ]);
                } else {
                    $presensi->update([
                        'terlambat' => 195,
                        'denda_terlambat' => 1.5,
                    ]);
                }
            }
        } elseif ($presensi->jam_masuk > $presensi->tanggal . ' ' . $jam->jam_masuk) {
            $terlambat = floor(Carbon::parse($presensi->jam_masuk)->diffInSeconds($presensi->tanggal . ' ' . $jam->jam_masuk) / 60);
            if ($terlambat >= 1 && $terlambat <= 30) {
                $denda_terlambat = 0.5;
            } elseif ($terlambat >= 31 && $terlambat <= 60) {
                $denda_terlambat = 1;
            } elseif ($terlambat >= 61 && $terlambat <= 90) {
                $denda_terlambat = 1.25;
            } elseif ($terlambat >= 91) {
                $denda_terlambat = 1.50;
            } else {
                $denda_terlambat = 0;
            }
            $presensi->update([
                'terlambat' => $terlambat,
                'denda_terlambat' => $denda_terlambat,
            ]);
        } else {
            $presensi->update([
                'terlambat' => 0,
                'denda_terlambat' => 0,
            ]);
        }

        // Calculate early departure (lebih_awal)
        if ($presensi->jam_pulang == null || Carbon::parse($presensi->jam_pulang)->format('H:i:s') == '00:00:00') {
            if (Pegawai::where('nip', $presensi->nip)->first()->jenis_presensi == 1) {
                if (Carbon::parse($presensi->tanggal)->translatedFormat('l') == 'Jumat') {
                    $presensi->update([
                        'lebih_awal' => 75,
                        'denda_lebih_awal' => 1.25,
                    ]);
                } else {
                    $presensi->update([
                        'lebih_awal' => 240,
                        'denda_lebih_awal' => 1.5,
                    ]);
                }
            } elseif (Pegawai::where('nip', $presensi->nip)->first()->jenis_presensi == 2) {
                if (Carbon::parse($presensi->tanggal)->translatedFormat('l') == 'Jumat') {
                    $presensi->update([
                        'lebih_awal' => 90,
                        'denda_lebih_awal' => 1.25,
                    ]);
                } elseif (Carbon::parse($presensi->tanggal)->translatedFormat('l') == 'Sabtu') {
                    $presensi->update([
                        'lebih_awal' => 165,
                        'denda_lebih_awal' => 1.5,
                    ]);
                } else {
                    $presensi->update([
                        'lebih_awal' => 195,
                        'denda_lebih_awal' => 1.5,
                    ]);
                }
            }
        } elseif ($presensi->jam_pulang < $presensi->tanggal . ' ' . $jam->jam_pulang) {
            $lebih_awal = floor(Carbon::parse($presensi->jam_pulang)->diffInSeconds($presensi->tanggal . ' ' . $jam->jam_pulang) / 60);
            if ($lebih_awal >= 1 && $lebih_awal <= 30) {
                $denda_lebih_awal = 0.5;
            } elseif ($lebih_awal >= 31 && $lebih_awal <= 60) {
                $denda_lebih_awal = 1;
            } elseif ($lebih_awal >= 61 && $lebih_awal <= 90) {
                $denda_lebih_awal = 1.25;
            } elseif ($lebih_awal >= 91) {
                $denda_lebih_awal = 1.50;
            } else {
                $denda_lebih_awal = 0;
            }
            $presensi->update([
                'lebih_awal' => $lebih_awal,
                'denda_lebih_awal' => $denda_lebih_awal,
            ]);
        } else {
            $presensi->update([
                'lebih_awal' => 0,
                'denda_lebih_awal' => 0,
            ]);
        }
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

        $data = Presensi::where('tanggal', $tanggal)->get();
        $totalData = $data->count();
        $counter = 0;

        $this->info("Tanggal : " . $tanggal);
        $this->info("Total Data : " . $totalData);
        $this->info("--eksekusi--");

        foreach ($data as $index => $item) {
            $counter++;
            if ($item->jam_masuk == null) {
                $item->update(['jam_masuk' => $item->tanggal . ' 00:00:00']);
            }

            if ($item->jam_pulang == null) {
                $item->update(['jam_pulang' => $item->tanggal . ' 00:00:00']);
            }


            if (Pegawai::where('nip', $item->nip)->first() == null) {
                $er = new ErrorData;
                $er->nip = $item->nip;
                $er->keterangan = 'menghitung terlambat tanggal ' . $tanggal;
                $er->save();
            } else {
                $checkJenisPresensi = Pegawai::where('nip', $item->nip)->first()->jenis_presensi;
            }
            //cek dia jenis presensi 5 hari kerja gak?

            if ($checkJenisPresensi == 1) {
                //cek dia tanggalnya weekend gak?
                if (Carbon::parse($item->tanggal)->isWeekend() == true) {
                    // weekend 
                    $item->update([
                        'terlambat' => 0,
                        'lebih_awal' => 0,
                        'denda_terlambat' => 0,
                        'denda_lebih_awal' => 0,
                    ]);
                } else {
                    //cek dia tanggalnya Libur nasional gak?
                    if (LiburNasional::where('tanggal', $item->tanggal)->first() != null) {
                        $item->update([
                            'jam_masuk' => $item->tanggal . ' 00:00:00',
                            'jam_pulang' => $item->tanggal . ' 00:00:00',
                            'terlambat' => 0,
                            'lebih_awal' => 0,
                            'denda_terlambat' => 0,
                            'denda_lebih_awal' => 0,
                        ]);
                    } else {
                        //cek dia TL / Cuti Tahunan gak?
                        // if ($item->jenis_keterangan_id == 4 || $item->jenis_keterangan_id == 5 || $item->jenis_keterangan_id == 7 || $item->jenis_keterangan_id == 9) {
                        if ($item->jenis_keterangan_id != null) {
                            $item->update([
                                'terlambat' => 0,
                                'lebih_awal' => 0,
                                'jenis_keterangan_id' => $item->jenis_keterangan_id,
                                'denda_terlambat' => 0,
                                'denda_lebih_awal' => 0,
                            ]);
                        } else {

                            $masuk = Carbon::parse($item->jam_masuk)->format('H:i');
                            $pulang = Carbon::parse($item->jam_pulang)->format('H:i');
                            if ($masuk == '00:00' && $pulang == '00:00') {
                                $item->update([
                                    'terlambat' => 0,
                                    'lebih_awal' => 0,
                                    'denda_terlambat' => 0,
                                    'denda_lebih_awal' => 0,
                                ]);
                            } else {
                                //check apakah ramadhan
                                $ramadhan = Ramadhan::where('tanggal', $item->tanggal)->first();
                                if ($ramadhan != null) {
                                    $hari = Carbon::parse($item->tanggal)->translatedFormat('l');
                                    $jam = JamRamadhan::where('hari', $hari)->first();

                                    $this->hitungTerlambatRamadhan($item, $jam);
                                    
                                    $pegawai = Pegawai::where('nip', $item->nip)->first();
                                    $this->info(($index + 1) . ". NIP : " . $item->nip . ", NAMA : " . ($pegawai ? $pegawai->nama : 'N/A') . ", denda_terlambat : " . $item->denda_terlambat . ", denda_lebih_awal : " . $item->denda_lebih_awal);
                                } else {
                                    $hari = Carbon::parse($item->tanggal)->translatedFormat('l');
                                    $jam = Jam::where('hari', $hari)->first();

                                    $this->hitungTerlambat($item, $jam);
                                    
                                    $pegawai = Pegawai::where('nip', $item->nip)->first();
                                    $this->info(($index + 1) . ". NIP : " . $item->nip . ", NAMA : " . ($pegawai ? $pegawai->nama : 'N/A') . ", denda_terlambat : " . $item->denda_terlambat . ", denda_lebih_awal : " . $item->denda_lebih_awal);
                                }
                            }
                        }
                    }
                }
            } elseif ($checkJenisPresensi == 2) {
                //cek dia tanggalnya minggu gak?
                if (Carbon::parse($item->tanggal)->translatedFormat('l') == 'Minggu') {
                    // minggu 
                    $item->update([
                        'terlambat' => 0,
                        'lebih_awal' => 0,
                        'denda_terlambat' => 0,
                        'denda_lebih_awal' => 0,
                    ]);
                } else {
                    //cek dia tanggalnya Libur nasional gak?
                    if (LiburNasional::where('tanggal', $item->tanggal)->first() != null) {
                        $item->update([
                            'jam_masuk' => $item->tanggal . ' 00:00:00',
                            'jam_pulang' => $item->tanggal . ' 00:00:00',
                            'terlambat' => 0,
                            'lebih_awal' => 0,
                            'denda_terlambat' => 0,
                            'denda_lebih_awal' => 0,
                        ]);
                    } else {
                        //cek dia TL / Cuti Tahunan gak?
                        // if ($item->jenis_keterangan_id == 4 || $item->jenis_keterangan_id == 5 || $item->jenis_keterangan_id == 7 || $item->jenis_keterangan_id == 9) {
                        if ($item->jenis_keterangan_id != null) {
                            $item->update([
                                'terlambat' => 0,
                                'lebih_awal' => 0,
                                'denda_terlambat' => 0,
                                'denda_lebih_awal' => 0,
                                'jenis_keterangan_id' => $item->jenis_keterangan_id,
                            ]);
                        } else {
                            $masuk = Carbon::parse($item->jam_masuk)->format('H:i');
                            $pulang = Carbon::parse($item->jam_pulang)->format('H:i');
                            if ($masuk == '00:00' && $pulang == '00:00') {
                                $item->update([
                                    'terlambat' => 0,
                                    'lebih_awal' => 0,
                                    'denda_terlambat' => 0,
                                    'denda_lebih_awal' => 0,
                                ]);
                            } else {
                                //check apakah ramadhan
                                $ramadhan = Ramadhan::where('tanggal', $item->tanggal)->first();
                                if ($ramadhan != null) {
                                    $hari = Carbon::parse($item->tanggal)->translatedFormat('l');
                                    $jam = Jam6Ramadhan::where('hari', $hari)->first();
                                    $this->hitungTerlambatRamadhan($item, $jam);
                                    
                                    $pegawai = Pegawai::where('nip', $item->nip)->first();
                                    $this->info(($index + 1) . ". NIP : " . $item->nip . ", NAMA : " . ($pegawai ? $pegawai->nama : 'N/A') . ", denda_terlambat : " . $item->denda_terlambat . ", denda_lebih_awal : " . $item->denda_lebih_awal);
                                } else {
                                    $hari = Carbon::parse($item->tanggal)->translatedFormat('l');
                                    $jam = Jam6::where('hari', $hari)->first();
                                    $this->hitungTerlambat($item, $jam);
                                    
                                    $pegawai = Pegawai::where('nip', $item->nip)->first();
                                    $this->info(($index + 1) . ". NIP : " . $item->nip . ", NAMA : " . ($pegawai ? $pegawai->nama : 'N/A') . ", denda_terlambat : " . $item->denda_terlambat . ", denda_lebih_awal : " . $item->denda_lebih_awal);
                                }
                            }
                        }
                    }
                }
            } else {
                $item->update([
                    'terlambat' => 0,
                    'lebih_awal' => 0,
                    'denda_terlambat' => 0,
                    'denda_lebih_awal' => 0,
                ]);
            }
        }
        
        $this->info("total selesai di update " . $counter);
        return 0;
    }
}
