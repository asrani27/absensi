@extends('layouts.app')

@push('css')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
  integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
  crossorigin="" />
<style>
  #mapid {
    height: 250px;
  }
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
          <img class="img-circle"
            src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/User_icon_2.svg/2048px-User_icon_2.svg.png"
            alt="User Image">
          <span class="username"><a href="#">{{Auth::user()->pegawai->nama}}</a></span>
          <span class="description">{{Auth::user()->pegawai->nip}}</span>
          <span class="description"></span>
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
            <a href="#" class="nav-link">Jarak
              <span class="float-right text-primary">
                <div id="jarak"></div>
              </span>
            </a>
          </li>
          <li class="nav-item">
            <a href="/pegawai/presensi/radius" class='btn btn-xs btn-primary btn-block'><i class="fas fa-sync"></i>
              Dapatkan Lokasi Saya</a>
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
        <div class="form-group row">
          <div class="col-12 text-center">

          </div>
        </div>
        <form id="radius" method="post" action="/pegawai/presensi/radius">
          @csrf
          <input type="hidden" name="datajarak" id="datajarak">
          <div class="form-group row">
            <div class="col-6 text-center">
              <strong>{{$jam_masuk == null ? '00:00:00': \Carbon\Carbon::parse($jam_masuk)->format('H:i:s')}}</strong>

              <input type="hidden" id="button" name="button">
            </div>
            <div class="col-6 text-center">
              <strong>{{$jam_pulang == null ? '00:00:00': \Carbon\Carbon::parse($jam_pulang)->format('H:i:s')}}</strong>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-12">
              <select class="form-control" name="lokasi_id" required>
                <option value="">-pilih lokasi-</option>
                @foreach ($pilih_lokasi as $item)
                <option value="{{$item->id}}">{{$item->nama}}</option>
                @endforeach
              </select>
              <br />
              <input type="hidden" name="lat" id="datalat">
              <input type="hidden" name="long" id="datalong">
              <button type="submit" class="btn btn-block bg-gradient-success btnMasuk" name="button">SIMPAN
                PRESENSI</button>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-12" id="btnLoading">
              <button type="button" class="btn btn-block bg-gradient-primary btnLoading"><i
                  class="fas fa-sync-alt fa-spin"></i> Menyimpan...</button>
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

@include('sweetalert::alert', ['cdn' => "https://cdn.jsdelivr.net/npm/sweetalert2@9"])
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
  integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
  crossorigin=""></script>

<script>
  //   function getLocation() {
//   if (navigator.geolocation) {
//     let giveUp = 1000 * 30;  //30 seconds
//     let tooOld = 1000 * 60 * 60;  //one hour
//         options ={
//             enableHighAccuracy: true,
//             timeout: giveUp,
//             maximumAge: tooOld
//         }
//     navigator.geolocation.getCurrentPosition(showPosition,posFail, options);
//   } else { 
//     x.innerHTML = "Geolocation is not supported by this browser.";
//   }
// }
// function showPosition(position) {
//   console.log(position.coords.latitude, position.coords.longitude);
// }

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
      document.getElementById("datalat").value = latlng.lat;
      document.getElementById("datalong").value = latlng.lng;

      console.log(distance,km,latlng2);
    });
</script>
@endpush