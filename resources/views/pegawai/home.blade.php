@extends('layouts.app')

@push('css')
    
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
crossorigin=""/>
<style>
    #mapid { height: 180px; }
</style>
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
                  <span class="description">{{Auth::user()->username}}</span>
                  <span class="description">{{$skpd->nama}}</span>
                </div>
            </div>
            <div class="card-footer bg-white p-0">
                <ul class="nav nav-pills flex-column">
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      Tanggal & Jam
                      <span class="float-right text-danger">{{\Carbon\Carbon::now()->isoFormat('D MMMM Y HH:mm')}}</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      Lokasi Presensi
                      <span class="float-right text-success">{{Auth::user()->pegawai->lokasi == null ? '-':Auth::user()->pegawai->lokasi->nama}}</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      Jarak Dengan Lokasi
                      <span class="float-right text-primary"><div id="jarak"></div></span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="/home/pegawai" class='btn btn-xs btn-primary btn-block'><i class="fas fa-sync"></i> Dapatkan Lokasi Saya</a>
                  </li>
                </ul>
            </div>    
        </div>
    </div>
</div>
<div class="row">
    @if (Auth::user()->pegawai->lokasi == null)
    <div class="col-12 col-sm-6 col-md-3">
    <div class="alert alert-danger alert-dismissible">
      <h5><i class="icon fas fa-ban"></i> Alert!</h5>
      Hubungi Admin SKPD anda untuk setting lokasi presensi
    </div>
    </div>
    @elseif(Auth::user()->pegawai->lokasi == null)
    <div class="col-12 col-sm-6 col-md-3">
    <div class="alert alert-danger alert-dismissible">
      <h5><i class="icon fas fa-ban"></i> Alert!</h5>
      Hubungi Admin SKPD anda untuk setting lokasi presensi
    </div>
    </div>
    @else
        
    <div class="col-12 col-sm-6 col-md-3">
      <a href="/pegawai/presensi/radius" style="color:black">
        <div class="info-box">
          <span class="info-box-icon bg-info elevation-1"><i class="fas fa-camera"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">PRESENSI RADIUS</span>
            <span class="info-box-number">
              Jarak + Photo
              <small></small>
            </span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </a>
    </div>
      <!-- /.col -->
    <div class="col-12 col-sm-6 col-md-3">
      <a href="/pegawai/presensi/barcode" style="color:black">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-qrcode"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">PRESENSI BARCODE</span>
          <span class="info-box-number">Scan Barcode</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      </a>
      <!-- /.info-box -->
    </div>
    <!-- fix for small devices only -->
    <div class="clearfix hidden-md-up"></div>

    <div class="col-12 col-sm-6 col-md-3">
      <a href="/pegawai/presensi/manual" style="color:black">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">PRESENSI MANUAL</span>
          <span class="info-box-number">Oleh Admin SKPD</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      </a>
      <!-- /.info-box -->
    </div>
    @endif
    <!-- /.col -->
    <div class="col-12 col-sm-6 col-md-3">
      <a href="/pegawai/presensi/history" style="color:black">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-chart-bar"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">HISTORY PRESENSI</span>
            <span class="info-box-number">Rekaman presensi</span>
            </div>
            <!-- /.info-box-content -->
        </div>
      </a>
    <!-- /.info-box -->
    </div>
</div>
{{-- <a href="/pegawai/presensi/testing">Test</a> --}}
<div class="row">
    <div class="col-lg-12">
        <div id="mapid"></div>
    </div>
</div>
        {{-- <div class="row">
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
        </div> --}}
        
@endsection 

@push('js')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
crossorigin=""></script>

<script>
    navigator.geolocation.getCurrentPosition(function(location) {
      var latlng = new L.LatLng(location.coords.latitude, location.coords.longitude);

      var mymap = L.map('mapid').setView(latlng, 16);
      googleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{
          maxZoom: 20,
          subdomains:['mt0','mt1','mt2','mt3']
      }).addTo(mymap);

      var latlng2 = {!!json_encode($latlong2)!!}
      var marker = L.marker(latlng).addTo(mymap);
      
      L.marker(latlng2).addTo(mymap);
      
      function calculateDistance(latA, latB) {
          if (latA !== undefined && latB !== undefined) {
              
              //How can I make it run distanceTo method here where leaflet Js is being called in another file 
              let dis = latA.distanceTo(latB);
              let distanceConversion = ((dis) / 1000).toFixed(0);
              let distanceKm = distanceConversion;
              return distanceKm || 0;
          }
          else {
              return 0;
          }
      }
      distance = calculateDistance(latlng,latlng2);

      var km = latlng.distanceTo(latlng2).toFixed(0);
      document.getElementById("jarak").innerHTML = km + ' Meter';
      
      // if(km > 0){
      // }else{
      // document.getElementById("jarak").innerHTML = 'Nyalakan GPS Anda';
      // }
      // console.log(distance,km);
    });

// $(document).ready(function(){
//   var cookieValue = document.getElementById("jarak").value;    
//   console.log(cookieValue);
//   if(typeof cookieValue === 'undefined'){
//       document.getElementById("jarak").innerHTML = 'Nyalakan GPS Anda';
//   }else{
//   }
// });
</script>
@endpush