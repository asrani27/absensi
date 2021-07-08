@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
crossorigin=""/>
<style>
    #mapid { height: 180px; }
    .new-button {
    display: inline-block;
    padding: 8px 12px; 
    cursor: pointer;
    border-radius: 4px;
    background-color: #9c27b0;
    font-size: 16px;
    color: #fff;
}
input[type="file"] {
  position: absolute;
  z-index: -1;
  top: 6px;
  left: 0;
  font-size: 15px;
  color: rgb(153,153,153);
}
.button-wrap {
  position: relative;
}

</style>
@endpush
@section('title')
  <strong>PRESENSI</strong>
@endsection
@section('content')
        <div class="card card-widget">
            <div class="card-header">
                <div class="user-block">
                  <img class="img-circle" src="/theme/dist/img/user1-128x128.jpg" alt="User Image">
                  <span class="username"><a href="#">{{Auth::user()->name}}</a></span>
                  <span class="description">{{$skpd->nama}}</span>
                </div>
              </div>    
        </div>

<div class="row">
    <div class="col-lg-12">
        <div id="mapid"></div>
        <div class="card">
        <div class="card-body">
            {{-- <div class="form-group row">
              <label class="col-sm-2 col-form-label">Latitude</label>
              <div class="col-sm-10">
                <input type="text" class="form-control form-control-sm"placeholder="Latitude">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Longitude</label>
              <div class="col-sm-10">
                <input type="text" class="form-control form-control-sm" placeholder="Longitude">
              </div>
            </div> --}}
            <form method="post" action="/pegawai/presensi/masuk">
              @csrf
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Jarak Dengan Pusat Presensi</label>
              <div class="col-sm-10">
                <input type="text" id="jarak" name="jarak" class="form-control form-control-sm" readonly>
              </div>
            </div>
            
            <div class="form-group row">
              <div class="custom-file">
                <input type="file" class="custom-file-input" id="customFile">
                <label class="custom-file-label" for="customFile">Choose file</label>
              </div>
            </div>
            <div class="form-group row">
                <button type="submit" class="btn btn-block btn-success">PRESENSI MASUK</button>
                <a href="/home/pegawai" class="btn btn-block btn-secondary">KEMBALI</a>
            </div>
            </form>
          </div>
        </div>
    </div>
</div>
@endsection

@push('js')

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
crossorigin=""></script>
<script src="/theme/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
  bsCustomFileInput.init();
});
</script>
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
      var km = latlng.distanceTo(latlng2).toFixed(0)/1000;
      document.getElementById("jarak").value = km;
      console.log(distance,km);
    });



</script>

@endpush