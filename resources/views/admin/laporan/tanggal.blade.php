<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    
<style media="print">
    @page {
      size: auto;  /* auto is the initial value */
      margin: 0mm; /* this affects the margin in the printer settings */
    }
</style>

</head>
<body>
    <p style="text-align: center;">{{strtoupper($skpd->nama)}}<br />TANGGAL : {{strtoupper(\Carbon\Carbon::parse($tanggal)->isoFormat('D MMMM Y'))}}<br />DATA PRESENSI<br /><br /></p>
<table style="border-collapse: collapse; width: 100%;" border="1">
<tbody>
<tr>
<td style="width: 5%; text-align: center;">No</td>
<td style="width: 25%; text-align: center;">NIP/Nama</td>
<td style="width: 25%; text-align: center;">Jam Masuk</td>
<td style="width: 25%; text-align: center;">Jam Pulang</td>
</tr>
@php
    $no=1;
@endphp
@foreach ($data as $item)
<tr style="font-size: 9px">
    <td style="width: 5%; text-align: center;">{{$no++}}</td>
    <td style="width: 25%; padding:2px 2px;">{{$item->nama}}<br/>{{$item->nip}}</td>
    <td style="width: 25%; text-align: center;">{{$item->presensi->jam_masuk == null ? '00:00:00':$item->presensi->jam_masuk}}</td>
    <td style="width: 25%; text-align: center;">{{$item->presensi->jam_pulang == null ? '00:00:00':$item->presensi->jam_pulang}}</td>
</tr>
@endforeach
</tbody>
</table>
</body>
<script>
    window.print();
</script>
</html>