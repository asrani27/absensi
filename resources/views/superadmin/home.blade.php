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
                    <img class="img-circle" src="/theme/dist/img/user1-128x128.jpg" alt="User Image">
                    <span class="username"><a href="#">{{Auth::user()->name}}</a></span>
                    <span class="description">SELAMAT DATANG DI APLIKASI PRESENSI</span>
                </div>
            </div>
        </div>
    </div>
</div>
<a href="/laporan2024" class="btn btn-md btn-success"><i class="fas fa-file"></i> Laporan 2024</a><br /><br />
<a href="/laporan24feb2025semua" class="btn btn-md btn-success" target="_blank"><i class="fas fa-file"></i>
    Laporan
    PRESENSI 24 FEBRUARI 2025</a>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                Laporan presensi Per Bulan
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
                            <td>{{\Carbon\Carbon::createFromFormat('m',$item->bulan)->translatedFormat('F')}}</td>
                            <td>{{$item->tahun}}</td>
                            <td><a href="/superadmin/rekapitulasi/{{$item->bulan}}/{{$item->tahun}}/skpd"
                                    class="btn btn-xs btn-success"><i class="fas fa-eye"></i> Detail</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
{{--
<div class="row">
    <div class="col-lg-12">
        <div class="card card-widget">
            <div class="card-body">
                <a href="/superadmin/hitungcuti" class="btn btn-outline-primary">Hitung Cuti</a>
                <a href="/superadmin/tarikpegawai" class="btn btn-outline-primary">Sinkron Data Pegawai Dari
                    TPP</a>
                <a href="/superadmin/limaharikerja" class="btn btn-outline-primary">Presensi 5 hari kerja</a>
                <a href="/superadmin/hitungpresensi" class="btn btn-outline-primary">Hitung Jumlah hari dan jam Bulan
                    Ini</a>
                <a href="/superadmin/ringkasanpegawai" class="btn btn-outline-primary">Masukkan Semua Pegawai Ke
                    Laporan
                    Bulanan</a>
                <a href="/superadmin/totalterlambat" class="btn btn-outline-primary">Hitung Total terlambat</a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form method="post" action="/superadmin/hitungterlambat">
                    @csrf
                    <input type="date" name="tanggal" value="{{old('tanggal')}}" required>
                    <button type="submit" class="btn btn-sm btn-outline-primary">Hitung Terlambat</button>
                </form>
            </div>
        </div>
    </div>
</div> --}}
@endsection

@push('js')

@endpush