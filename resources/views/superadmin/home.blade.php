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
<div class="row">
    <div class="col-lg-12">
        <div class="card card-widget">
            <div class="card-body">
                <a href="/superadmin/tarikpegawai" class="btn btn-outline-primary">Sinkron Data Pegawai Dari TPP</a>
                <a href="/superadmin/limaharikerja" class="btn btn-outline-primary">Presensi 5 hari kerja</a>
                <a href="/superadmin/hitungpresensi" class="btn btn-outline-primary">Hitung Jumlah hari dan jam Bulan
                    Ini</a>
                <a href="/superadmin/ringkasanpegawai" class="btn btn-outline-primary">Masukkan Semua Pegawai Ke
                    Laporan
                    Bulanan</a>
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
</div>
@endsection

@push('js')

@endpush