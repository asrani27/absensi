<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta content="en-us" http-equiv="Content-Language" />
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>Presensi</title>
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
        font-size: 12px;
        font-family: Arial, Helvetica, sans-serif;
        }

        td {
            font-weight: bold;
            border: 2px solid #000;
            font-size: 10px;
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
        <p style="text-align: center"><span class="auto-style1"><strong>LAPORAN PRESENSI 24 FEBRUARI 2025
                </strong></span><strong><br class="auto-style1" />
            </strong><span class="auto-style1"><strong>{{strtoupper(Auth::user()->skpd->nama)}}</strong></span></p>
    </header>

    <main>
        <table width="100%" cellpadding="6" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Presensi Masuk</th>
                    <th>Lokasi</th>
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
                    <td style="text-align: center">
                        {{\Carbon\Carbon::parse($item->jam_masuk_hari_besar)->format('H:i:s')}}</td>
                    <td>{{$item->lokasiabsenmasuk == null ? '-' : $item->lokasiabsenmasuk->nama}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </main>
</body>

</html>