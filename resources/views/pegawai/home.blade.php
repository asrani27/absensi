@extends('layouts.app')

@push('css')
    
@endpush
@section('title')
  <strong>PRESENSI</strong>
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-widget">
            <div class="card-header">
                <div class="user-block">
                  <img class="img-circle" src="/theme/dist/img/user1-128x128.jpg" alt="User Image">
                  <span class="username"><a href="#">{{Auth::user()->name}}</a></span>
                  <span class="description">Jabatan</span>
                </div>
              </div>    
        </div>
    </div>
</div>
        <div class="row">
            <div class="col-lg-6 col-6">
                <a href="/pegawai/presensi/masuk">
                <div class="info-box bg-gradient-success">
                <div class="info-box-content text-center">
                    <span class="info-box-text"><i class="far fa-calendar"></i><br/><strong>PRESENSI MASUK</strong></span>              
                </div>
                </div>
                </a> 
            </div>
            <div class="col-lg-6 col-6">
                <a href="/pegawai/presensi/pulang">
                <div class="info-box bg-gradient-danger">
                <div class="info-box-content text-center">
                    <span class="info-box-text"><i class="far fa-calendar"></i><br/><strong>PRESENSI PULANG</strong></span>               
                </div>
                </div>
                </a>
            </div>
        </div>
        
@endsection

@push('js')

@endpush