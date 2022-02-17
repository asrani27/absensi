@extends('layouts.app')

@push('css')

@endpush
@section('title')
<strong>PRESENSI</strong>
@endsection
@section('content')
<br />
<div class="row">
    <div class="col-lg-12">
        <div class="card card-widget">
            <div class="card-header">
                <div class="user-block">
                    <img class="img-circle"
                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/User_icon_2.svg/2048px-User_icon_2.svg.png"
                        alt="User Image">
                    <span class="username"><a href="#">{{$pegawai->nama}}</a></span>
                    <span class="description">{{$pegawai->nip}} <br />{{$pegawai->skpd->nama}} -
                        {{$pegawai->puskesmas_id == null ? '':$pegawai->puskesmas->nama}}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                Data Riwayat Presensi
            </div>
            <div class="card-body">
                <a href="/puskesmas/pegawai/{{$id}}/presensi" class="btn btn-xs btn-secondary"><i
                        class="fas fa-arrow-left"></i> Kembali</a><br /><br />
                <table class="table table-hover table-striped text-nowrap table-sm">
                    <thead>
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif"
                            class="bg-gradient-primary">
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Hari</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Keterangan</th>
                            <th>Telat</th>
                            <th>Lebih awal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    @php
                    $no =1;
                    @endphp
                    <tbody>
                        @foreach ($data as $key => $item)
                        @if (\Carbon\Carbon::parse($item->tanggal)->translatedFormat('l') === 'Minggu')
                        <tr style="background-color: #f2dede;font-size:11px; font-family:Arial, Helvetica, sans-serif">
                            @else
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif">
                            @endif
                            <td>{{$no++}}</td>
                            <td>{{\Carbon\Carbon::parse($item->tanggal)->format('d M Y')}}</td>
                            <td>{{\Carbon\Carbon::parse($item->tanggal)->translatedFormat('l')}}</td>
                            <td>{{$item->jam_masuk == null ? '00:00:00': $item->jam_masuk}}</td>
                            <td>{{$item->jam_pulang == null ? '00:00:00': $item->jam_pulang}}</td>
                            <td>
                                @if ($item->jenis_keterangan_id == 5 || $item->jenis_keterangan_id == 7 ||
                                $item->jenis_keterangan_id == 9)
                                {{$item->jenis_keterangan->keterangan}}
                                @else
                                {{$item->keterangan}}
                                @endif
                            </td>
                            <td>{{$item->terlambat}}</td>
                            <td>{{$item->lebih_awal}}</td>
                            <td><a href="/puskesmas/pegawai/{{$id}}/presensi/{{$bulan}}/{{$tahun}}/{{$item->id}}/edit"><i
                                        class="fas fa-edit"></i> Edit</a>
                            </td>
                        </tr>
                        @endforeach
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Total :</td>
                            <td>{{$data->sum('terlambat')}} menit</td>
                            <td>{{$data->sum('lebih_awal')}} menit</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')

@endpush