@extends('layouts.app')

@push('css')
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
    SKPD 
@endsection
@section('content')
<br/>
<div class="row">
    <div class="col-lg-12 col-12">
        <div class="card card-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-info">
              <div class="widget-user-image">
                <img class="img-circle elevation-2" src="/theme/logo.png" alt="User Avatar">
              </div>
              <!-- /.widget-user-image -->
              <h3 class="widget-user-username">{{$skpd->nama}}</h3>
              <h5 class="widget-user-desc">Kode Skpd: {{$skpd->kode_skpd}}</h5>
            </div>
            
          </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box">
        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Pegawai</span>
          <span class="info-box-number">
            {{$countPegawai}} <a href="/superadmin/skpd/{{$skpd->id}}/pegawai" class="btn btn-sm"><i class="fas fa-sign-out-alt"></i></a>
            <small></small>
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-map-marker"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Lokasi</span>
          <span class="info-box-number">4<a href="#" class="btn btn-sm"><i class="fas fa-sign-out-alt"></i></a></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix hidden-md-up"></div>

    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-qrcode"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Qr Code</span>
          <span class="info-box-number">7<a href="#" class="btn btn-sm"><i class="fas fa-sign-out-alt"></i></a></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-file"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Laporan</span>
          <span class="info-box-number">2<a href="#" class="btn btn-sm"><i class="fas fa-sign-out-alt"></i></a></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>

@if ($page == null)
@elseif($page == 'pegawai')
@include('superadmin.skpd.pegawai')
@endif
@endsection

@push('js')
@endpush