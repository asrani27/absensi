@extends('layouts.app')

@push('css')
    
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
crossorigin=""/>
<style>
    #mapid { height: 10px; }
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
                  <img class="img-circle" src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/User_icon_2.svg/2048px-User_icon_2.svg.png" alt="User Image">
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
                    <a href="/pegawai/presensi/radius" class='btn btn-xs btn-primary btn-block'><i class="fas fa-sync"></i> Dapatkan Lokasi Saya</a>
                    {{-- <a href="/home/pegawai" class='btn btn-xs btn-secondary btn-block'><i class=""></i> Kembali</a> --}}
                  </li>
                </ul>
            </div>    
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="/pegawai/presensi/radius" enctype="multipart/form-data"  id="radius">
                    @csrf
                <div class="form-group row">
                    <input type="file" name="file" class="form-control">
                    <input type="hidden" name="browser" class="form-control" value="{{$os}}">
                    <input type="hidden" id="button" name="button">
                </div>
                <input type="hidden" name="datajarak" id="datajarak">
                <div class="form-group row">
                    <div class="col-6 text-center">
                        <strong>{{$jam_masuk == null ? '00:00:00': $jam_masuk}}</strong>
                    </div>
                    <div class="col-6 text-center">
                        <strong>{{$jam_pulang == null ? '00:00:00': $jam_pulang}}</strong>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-6">
                        <button type="submit" class="btn btn-block bg-gradient-success btnMasuk" name="button" value="masuk">MASUK</button>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn btn-block bg-gradient-danger btnPulang" name="button" value="pulang">PULANG</button>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-12" id="btnLoading">
                      <button type="button" class="btn btn-block bg-gradient-primary btnLoading"><i class="fas fa-sync-alt fa-spin"></i> Menyimpan...</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <div id="mapid"></div>
    </div>
</div>
       
@endsection 

@push('js')

<script>
    $(document).ready(function () {
        
        var loading = document.getElementById("btnLoading");
        loading.style.display = "none";

      $("#radius").submit(function () {
        $(".btnMasuk").hide();
        //$(".btnMasuk").attr("disabled", true);  
        document.getElementById("button").value = "masuk";
        loading.style.display = "block";
        return true;
      });

      $("#radius").submit(function () {
        $(".btnPulang").hide();
        //$(".btnPulang").attr("disabled", true);  
        document.getElementById("button").value = "pulang";
        loading.style.display = "block";
        return true;
      });
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
      document.getElementById("datajarak").value = km;
      console.log(distance,km,latlng2);
    });
</script>
@endpush