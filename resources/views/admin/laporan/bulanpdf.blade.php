<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta content="en-us" http-equiv="Content-Language" />
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>Untitled 1</title>
    {{-- <style type="text/css">
        .auto-style1 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: x-small;
        }
    </style> --}}
    <style>
        @page {
            margin-top: 80px;
            margin-left: 50px;
        }

        header {
            position: fixed;
            top: -60px;
            left: 0px;
            right: 0px;
            height: 0px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            /** Extra personal styles **/
            /* background-color: #03a9f4;
            color: white;
            text-align: center; 
            line-height: 35px;*/
        }

        tr,
        th,
            {
            border: 2px solid #000;
            font-size: 10px;
            font-family: Arial, Helvetica, sans-serif;
        }

        td {
            font-weight: bold;
            border: 2px solid #000;
            font-size: 8px;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 50px;
            font-size: 8px;
            font-family: Arial, Helvetica, sans-serif;
            /** Extra personal styles **/
            /* background-color: #03a9f4;
            color: white;
            text-align: center;
            line-height: 35px; */
        }
    </style>
</head>

<body>
    <header>
        <p><span class="auto-style1"><strong>LAPORAN RINGKASAN KEHADIRAN PEGAWAI PER
                    PERIODE</strong></span><strong><br class="auto-style1" />
            </strong><span class="auto-style1"><strong>{{strtoupper($skpd->nama)}}</strong></span><strong><br
                    class="auto-style1" />
            </strong><span class="auto-style1"><strong>Periode : {{$mulai}} - {{$sampai}}
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Tanggal Cetak : {{\Carbon\Carbon::now()->format('d-m-Y H:i:s')}}</strong></span></p>
    </header>
    <footer>
        <hr>
        <p>*Ketidakhadiran: A=Alpha ; S=Sakit ; TR=Training / Diklat ; D=Tugas Kantor / Dinas / SPPD ; I=Ijin ; C=Cuti ;
            L=Lain-lain ; O=Off / Hari Libur
        </p>
    </footer>
    <main>
        <table cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">NIP</th>
                    <th rowspan="2">Nama</th>
                    <th rowspan="2">Jabatan</th>
                    <th rowspan="2">Jum<br /> hari</th>
                    <th colspan="2">Hadir di hari</th>
                    <th colspan="8">ketidakhadiran</th>
                    <th colspan="2">Total Absensi</th>
                    <th rowspan="2">Jam Kerja<br /> Pegawai</th>
                    <th rowspan="2">Datang<br /> Lambat</th>
                    <th rowspan="2">Pulang<br /> Cepat</th>
                    <th rowspan="2">%</th>
                    <th rowspan="2">Total<br /> Hari<br /> Kerja</th>
                </tr>
                <tr>
                    <th>Kerja</th>
                    <th>Libur</th>
                    <th>A</th>
                    <th>S</th>
                    <th>TR</th>
                    <th>D</th>
                    <th>I</th>
                    <th>C</th>
                    <th>L</th>
                    <th>O</th>
                    <th>Masuk</th>
                    <th>keluar</th>
                </tr>
            </thead>
            <tbody>
                @php
                $no = 1;
                @endphp
                @foreach ($data as $item)
                <tr>
                    <td>{{$no++}}</td>
                    <td>{{$item->nip}}</td>
                    <td>{{strtoupper($item->nama)}}</td>
                    <td>{{Str::limit(strtoupper($item->jabatan), 50)}}</td>
                    <td>{{$item->jumlah_hari}}</td>
                    <td>{{$item->kerja}}</td>
                    <td>0</td>
                    <td align="center">{{$item->a == null ? '0': $item->a}}</td>
                    <td align="center">{{$item->s == null ? '0': $item->s}}</td>
                    <td align="center">{{$item->tr == null ? '0': $item->tr}}</td>
                    <td align="center">{{$item->d == null ? '0': $item->d}}</td>
                    <td align="center">{{$item->i == null ? '0': $item->i}}</td>
                    <td align="center">{{$item->c == null ? '0': $item->c}}</td>
                    <td align="center">{{$item->l == null ? '0': $item->l}}</td>
                    <td align="center">{{$item->o == null ? '0': $item->o}}</td>
                    <td>{{$item->masuk}}</td>
                    <td>{{$item->keluar}}</td>
                    <td>{{intdiv(($item->jumlah_jam - $item->datang_lambat - $item->pulang_cepat),
                        60)}}:{{($item->jumlah_jam
                        - $item->datang_lambat - $item->pulang_cepat) % 60}}</td>
                    <td>{{intdiv($item->datang_lambat, 60)}}:{{$item->datang_lambat % 60}}</td>
                    <td>{{intdiv($item->pulang_cepat, 60)}}:{{$item->pulang_cepat % 60}}</td>
                    <td>{{$item->persen_kehadiran}}</td>
                    <td>{{$item->kerja}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </main>
    {{-- <p><span class="auto-style1"><strong>LAPORAN RINGKASAN KEHADIRAN PEGAWAI PER
                PERIODE</strong></span><strong><br class="auto-style1" />
        </strong><span class="auto-style1"><strong>DINAS</strong></span><strong><br class="auto-style1" />
        </strong><span class="auto-style1"><strong>Periode
                :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Tanggal Cetak</strong></span></p> --}}

</body>

</html>