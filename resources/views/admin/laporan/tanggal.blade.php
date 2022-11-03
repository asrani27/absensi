<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta content="en-us" http-equiv="Content-Language" />
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
	<style type="text/css">
		.auto-style1 {
			text-align: center;
		}

		.auto-style2 {
			border: 0px solid #000000;
		}

		.auto-style3 {
			border-style: solid;
			border-width: 1px;
			text-align: center;
			font-size: 10px;
		}

		.auto-style4 {
			border-style: solid;
			border-width: 1px;
			font-size: 10px;
		}

		.auto-style5 {
			font-size: 10px;
		}
	</style>
</head>

<body>

	<p class="auto-style1"><strong>DAFTAR HADIR PEGAWAI NEGERI SIPIL {{strtoupper($skpd->nama)}}<br />
			KOTA BANJARMASIN</strong></p>
	<table style="width: 100%">
		<tr>
			<td style="width: 87px"><strong>HARI</strong></td>
			<td><strong>: {{strtoupper(\Carbon\Carbon::parse($tanggal)->translatedFormat('l'))}}</strong></td>
		</tr>
		<tr>
			<td style="width: 87px"><strong>TANGGAL</strong></td>
			<td><strong>: {{strtoupper(\Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y'))}}</strong></td>
		</tr>
	</table>

	<br />

	<table style="width: 100%" cellpadding="5" cellspacing="0" class="auto-style2">
		<thead>
			<tr>
				<td class="auto-style3" style="height: 24px"><strong>NO</strong></td>
				<td class="auto-style3" style="height: 24px; width:30%;"><strong>NAMA / NIP</strong></td>
				<td class="auto-style3" style="height: 24px; width:20%;"><strong>PANGKAT</strong></td>
				<td class="auto-style3" style="height: 24px"><strong>JABATAN</strong></td>
				<td class="auto-style3" style="height: 24px"><strong>PAGI</strong></td>
				<td class="auto-style3" style="height: 24px"><strong>PULANG</strong></td>
			</tr>
		</thead>
		@php
		$no=1;
		@endphp
		@foreach ($data as $item)
		<tbody>
			<tr>
				<td class="auto-style3">{{$no++}}</td>
				<td class="auto-style4">{{$item->nama}} <br /> {{$item->nip}}</td>
				<td class="auto-style3">{{$item->pangkat}}<br />({{$item->golongan}})</td>
				<td class="auto-style3">{{$item->jabatan}}</td>
				
				<td class="auto-style3">{{$item->presensi->jam_masuk == null ? '' :
					\Carbon\Carbon::parse($item->presensi->jam_masuk)->format('H:i:s')}}</td>
				<td class="auto-style3">{{$item->presensi->jam_pulang == null ? '' :
					\Carbon\Carbon::parse($item->presensi->jam_pulang)->format('H:i:s')}}</td>
			</tr>
		</tbody>
		@endforeach
	</table>
	<br />
	<table style="width: 100%" cellpadding="5" cellspacing="0" class="auto-style2">
		<tfoot>
			<tr>
				<td width="50%"></td>
				<td></td>
				<td></td>
				<td></td>
				<td colspan=2 class="auto-style5">{{strtoupper($pimpinan->jabatan)}}</td>
			</tr>
			<tr>
				<td width="50%"></td>
				<td></td>
				<td></td>
				<td></td>
				<td colspan=2></td>
			</tr>
			<tr>
				<td width="50%"></td>
				<td></td>
				<td></td>
				<td></td>
				<td colspan=2></td>
			</tr>
			<tr>
				<td width="50%"></td>
				<td></td>
				<td></td>
				<td></td>
				<td colspan=2></td>
			</tr>
			<tr>
				<td width="50%"></td>
				<td></td>
				<td></td>
				<td></td>
				<td colspan=2></td>
			</tr>
			<tr>
				<td width="50%"></td>
				<td></td>
				<td></td>
				<td></td>
				<td colspan=2 class="auto-style5">{{$pimpinan->nama}}<br />NIP.{{$pimpinan->nip}}</td>
			</tr>
		</tfoot>
	</table>


</body>
<script>
	window.print();
</script>

</html>