<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th colspan="7">{{Auth::user()->skpd->nama}}</th>
            </tr>
            <tr>
                <th colspan="7">Tanggal : {{\Carbon\Carbon::parse($tanggal)->format('d M Y')}}</th>
            </tr>
            <tr>
                <th rowspan="2">NO</th>
                <th rowspan="2">NIP</th>
                <th rowspan="2">NAMA</th>
                <th rowspan="2">SKPD</th>
                <th colspan="2">PRESENSI HARIAN</th>
                <th colspan="2">PRESENSI HARI BESAR</th>
            </tr>
            <tr>
                <td>Masuk</td>
                <td>Pulang</td>
                <td>Masuk</td>
                <td>Pulang</td>
            </tr>
        </thead>
        <tbody>
            @foreach($presensi as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>'{{ $item->nip }}</td>
                <td>{{ strtoupper($item->nama) }}</td>
                <td>{{ $item->skpd == null ? null : strtoupper($item->skpd->nama) }}</td>
                {{-- <td>{{ strtoupper($item->puskesmas == null ? null : $item->puskesmas->nama) }}
                </td> --}}
                <td>{{ \Carbon\Carbon::parse($item->jam_masuk)->format('H:i:s') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->jam_pulang)->format('H:i:s') }}</td>
                {{-- <td>
                    @if ($item->jam_masuk_hari_besar == null)
                    {{ \Carbon\Carbon::parse($item->jam_masuk)->format('H:i:s') }}
                    @else
                    {{ \Carbon\Carbon::parse($item->jam_masuk_hari_besar)->format('H:i:s') }}
                    @endif
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($item->jam_pulang)->format('H:i:s') }}
                </td> --}}
                <td>{{$item->jam_masuk_hari_besar == null ? null : $item->jam_masuk_hari_besar}}</td>
                <td>{{$item->jam_pulang_hari_besar == null ? null : $item->jam_pulang_hari_besar}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>