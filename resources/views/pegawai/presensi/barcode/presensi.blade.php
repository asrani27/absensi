@extends('layouts.app')

@push('css')
    
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
crossorigin=""/>
<style>
    #mapid { height: 10px; }
    /* #preview{
      -o-transform : scaleX(-1);
      -moz-transform : scaleX(-1);
      -webkit-transform : scaleX(-1);
      -ms-transform: scaleX(-1);
      transform : scaleX(-1);
} */
</style>
<script src="/instascan/instascan.min.js"></script>
{{-- <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script> --}}
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
                    <a href="/pegawai/presensi/barcode" class='btn btn-xs btn-primary btn-block'><i class="fas fa-sync"></i> Dapatkan Lokasi Saya</a>
                    <a href="/home/pegawai" class='btn btn-xs btn-secondary btn-block'><i class=""></i> Kembali</a>
                  </li>
                </ul>
            </div>    
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body text-center">
              @if (strtotime('now') < strtotime('12:00pm'))
                  <span class="badge badge-warning">Anda Berada Di Scan Masuk</span>
              @else
                  <span class="badge badge-warning">Anda Berada Di Scan Pulang</span>
              @endif
              <video id="preview" width="150%" height="200" playsinline></video>
                <form method="post" name="myForm" id="myForm" action="/pegawai/presensi/barcode/scan">
                    @csrf
                    @if (strtotime('now') < strtotime('12:00pm'))
                      <input type="hidden" name="jenis" value="masuk">
                    @else
                      <input type="hidden" name="jenis" value="pulang">
                    @endif
                    {{-- <div class="form-group row">
                        <div class="col-12">
                          <div class="form-group row">
                              <div class="col-6">
                                  <a href="/pegawai/presensi/barcode/front" class="btn btn-block bg-gradient-success" name="button" value="masuk">Front Cam</a>
                              </div>
                              <div class="col-6">
                                <a href="/pegawai/presensi/barcode/back" class="btn btn-block bg-gradient-secondary" name="button" value="masuk">Back Cam</a>
                              </div>
                          </div>
                        </div>
                    </div> --}}
                </form>
            </div>
        </div>
        <div id="mapid"></div>
    </div>
</div>
        
@endsection 

@push('js')

<script type="text/javascript">
    let scanner = new Instascan.Scanner({ 
      video: document.getElementById('preview'),
      mirror:false 
    });
    scanner.addListener('scan', function (content) {
      document.forms["myForm"].submit();
      document.getElementById('loadingGif').style.display = "block";
    });
    Instascan.Camera.getCameras().then(function (cameras) {
      
      if (cameras.length > 0) {
        scanner.start(cameras[0]);
      } else {
        console.error('No cameras found.');
      }
    }).catch(function (e) {
        alert(e+'oke');
  //        console.error(e);
    });
  </script>
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
      console.log(distance,km);
    });
</script>
@endpush