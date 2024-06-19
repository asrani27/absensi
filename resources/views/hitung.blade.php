<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table border=1>
        <tr>
            <th>No</th>
            <th>NIP</th>
            <th>Nama</th>
            <th>Jumlah Terlambat</th>
            <th>Jumlah Pulang Lebih Awal</th>
        </tr>
        @foreach ($data as $key => $item)
            <tr>
                <td>
                    {{$key + 1}}
                </td>
                <td>
                    {{$item->nip}}
                </td>
                <td>
                    {{$item->nama}}
                </td>
                <td>
                    {{$item->terlambat}}
                </td>
                <td>
                    {{$item->lebih_awal}}
                </td>
            </tr>
        @endforeach
    </table>
</body>
</html>