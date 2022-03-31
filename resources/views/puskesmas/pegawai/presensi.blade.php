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
                <table class="table table-hover table-striped text-nowrap table-sm">
                    <thead>
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif"
                            class="bg-gradient-primary">
                            <th>#</th>
                            <th>Bulan</th>
                            <th>Tahun</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    @php
                    $no =1;
                    @endphp
                    <tbody>

                        @foreach (bulanTahun() as $key => $item)
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif">
                            <td>{{$no++}}</td>
                            <td>{{convertBulan($item->bulan)}}</td>
                            <td>{{$item->tahun}}</td>
                            <td><a href="/puskesmas/pegawai/{{$id}}/presensi/{{$item->bulan}}/{{$item->tahun}}"
                                    class="btn btn-xs btn-success"><i class="fas fa-eye"></i> Detail</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')

@endpush