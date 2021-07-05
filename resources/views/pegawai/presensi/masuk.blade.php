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
                  <span class="description">Jabatan</span>
                </div>
              </div>    
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div id="mapid"></div>
        <div class="card">
        <div class="card-body">
            <div class="form-group row">
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
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Jarak Dengan Pusat Presensi</label>
              <div class="col-sm-10">
                <input type="text" class="form-control form-control-sm" placeholder="10 Meter">
              </div>
            </div>
            <div class="form-group row">
                <button type="submit" class="btn btn-block btn-success">PRESENSI MASUK</button>
                <a href="/home/pegawai" class="btn btn-block btn-secondary">KEMBALI</a>
            </div>
          </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
crossorigin=""></script>


<script>
    navigator.geolocation.getCurrentPosition(function(location) {
    var latlng = new L.LatLng(location.coords.latitude, location.coords.longitude);

    var mymap = L.map('mapid').setView(latlng, 18);
    googleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{
        maxZoom: 20,
        subdomains:['mt0','mt1','mt2','mt3']
    }).addTo(mymap);
    // L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
    //     attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    //     }).addTo(mymap);

    var marker = L.marker(latlng).addTo(mymap);
    });
</script>
@endpush