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
                <th>NO</th>
                <th>NIP</th>
                <th>NAMA</th>
                <th>SKPD</th>
                <th>PUSKESMAS</th>
                <th>JAM ABSEN</th>
                <th>LOKASI</th>
            </tr>
        </thead>
        <tbody>
            @foreach($presensi as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>'{{ $item->nip }}</td>
                <td>{{ strtoupper($item->nama) }}</td>
                <td>{{ $item->skpd == null ? null : strtoupper($item->skpd->nama) }}</td>
                <td>{{ strtoupper($item->puskesmas == null ? null : $item->puskesmas->nama) }}</td>
                <td>{{ \Carbon\Carbon::parse($item->jam_masuk_hari_besar)->format('H:i') }}</td>
                <td>{{$item->jam_masuk_hari_besar == null ? null : 'Halaman Balai Kota'}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>