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
{{-- <div class="row">
    <div class="col-lg-12">
        <div class="card card-widget">
            <div class="card-body">
                <a href="/superadmin/notnull" class="btn btn-outline-primary">Generate NULL jam masuk & pulang</a>
                <a href="/superadmin/tarikpegawai" class="btn btn-outline-primary">Tarik Data Pegawai Dari TPP</a>
            </div>
        </div>
    </div>
</div> --}}
@endsection

@push('js')

@endpush