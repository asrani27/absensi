<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>List Loss</title>
</head>
<body>
    @php
        $no = 1;
    @endphp
    <table border=1 cellspacing=0 cellpadding=10>
        @foreach ($data as $item)
            
        <tr>
            <td>{{$no++}}</td>
            <td>
                {{$item->nip}}<br/>
                {{$item->nama}}<br/>
                {{$item->jabatan}}<br/>
                
            </td>
            <td>{{$item->skpd}}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>