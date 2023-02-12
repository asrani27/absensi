@extends('layouts.app')

@push('css')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
  integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
  crossorigin="" />
<style>
  #mapid {
    height: 180px;
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
            <a href="https://play.google.com/store/apps/details?id=com.diskominfotik.banjarmasin" class="nav-link">
              TERSEDIA DI PLAYSTORE
              <span class="float-right text-primary"><strong>klik disini</strong></span>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              Tanggal & Jam
              <span class="float-right text-danger">{{\Carbon\Carbon::now()->isoFormat('D MMMM Y HH:mm')}}</span>
            </a>
          </li>
          {{-- <li class="nav-item">
            <a href="#" class="nav-link">
              Lokasi Presensi
              <span class="float-right text-success">{{Auth::user()->pegawai->lokasi == null ?
                '-':Auth::user()->pegawai->lokasi->nama}}</span>
            </a>
          </li> --}}
          <li class="nav-item">
            <a href="#" class="nav-link">
              Jenis Presensi
              <span class="float-right text-success">
                @if (Auth::user()->pegawai->jenis_presensi == 1)
                5 Hari Kerja
                @elseif (Auth::user()->pegawai->jenis_presensi == 2)
                6 Hari Kerja
                @else
                Shift
                @endif
                {{-- {{Auth::user()->pegawai->jenis_presensi == null ?
                '-':Auth::user()->pegawai->lokasi->nama}} --}}
              </span>
            </a>
          </li>
          @if (Auth::user()->pegawai->jenis_presensi == 3)

          @else

          <li class="nav-item">
            <a href="#" class="nav-link">
              Jam Masuk
              <span class="float-right text-primary">{{\Carbon\Carbon::parse($rentang->jam_masuk_mulai)->format('H:i')}}
                - {{\Carbon\Carbon::parse($rentang->jam_masuk_selesai)->format('H:i')}}</span>
            </a>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              Jam Pulang
              <span
                class="float-right text-primary">{{\Carbon\Carbon::parse($rentang->jam_pulang_mulai)->format('H:i')}} -
                {{\Carbon\Carbon::parse($rentang->jam_pulang_selesai)->format('H:i')}}</span>
            </a>
          </li>
          @endif
          {{-- <li class="nav-item">
            <a href="#" class="nav-link">
              Jarak Dengan Lokasi
              <span class="float-right text-primary">
                <div id="jarak"></div>
              </span>
            </a>
          </li> --}}
          {{-- <li class="nav-item">
            <a href="/home/pegawai" class='btn btn-xs btn-primary btn-block'><i class="fas fa-sync"></i> Dapatkan Lokasi
              Saya</a>
          </li> --}}
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="row">
  
  @if (isVerifikator(Auth::user()->username) == true)
  <div class="col-12 col-sm-6 col-md-3">
    <a href="/pegawai/presensi/verifikator" style="color:black">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">VERIFIKATOR</span>
          <span class="info-box-number">Permohonan Perbaikan Data</span>
        </div>
      </div>
    </a>
  </div> 
  @endif
  

  @if (Auth::user()->pegawai->lokasi == null)
  <div class="col-12 col-sm-6 col-md-3">
    <div class="alert alert-danger alert-dismissible">
      <h5><i class="icon fas fa-ban"></i> Alert!</h5>
      Silahkan Pilih Lokasi Presensi
      <form method="post" action="/pegawai/lokasi">
        @csrf
        <select name="lokasi_id" class="form-control" required>
          <option value="">-pilih lokasi-</option>
          @foreach ($lokasi as $item)
          <option value="{{$item->id}}">{{$item->nama}}</option>
          @endforeach
        </select><br />
        <button type="submit" class="btn btn-success btn-sm"
          onclick="return confirm('Yakin ingin disimpan?')">simpan</button>
      </form>
    </div>
  </div>
  @elseif($cuti != null)
  <div class="col-12 col-sm-6 col-md-3">
    <div class="alert alert-info alert-dismissible">
      <h5><i class="icon fas fa-ban"></i> Info!</h5>
      Anda Sedang {{$cuti->jenis_keterangan->keterangan}} Mulai : <br />
      {{\Carbon\Carbon::parse($cuti->tanggal_mulai)->isoFormat('D MMMM Y')}} -
      {{\Carbon\Carbon::parse($cuti->tanggal_selesai)->isoFormat('D MMMM Y')}}
    </div>
  </div>
  @else


  @if (Auth::user()->pegawai->jenis_presensi == 3)

  <div class="col-12 col-sm-6 col-md-3">
    <a href="/pegawai/presensi/pagi" style="color:black">
      <div class="info-box">
        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-camera"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">PRESENSI PAGI</span>
          <span class="info-box-number">
            Jarak
            <small></small>
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </a>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <a href="/pegawai/presensi/siang" style="color:black">
      <div class="info-box">
        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-camera"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">PRESENSI SIANG</span>
          <span class="info-box-number">
            Jarak
            <small></small>
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </a>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <a href="/pegawai/presensi/malam" style="color:black">
      <div class="info-box">
        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-camera"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">PRESENSI MALAM</span>
          <span class="info-box-number">
            Jarak
            <small></small>
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </a>
  </div>
  @else

  <div class="col-12 col-sm-6 col-md-3">
    <a href="/pegawai/presensi/radius" style="color:black">
      <div class="info-box">
        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-camera"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">PRESENSI RADIUS</span>
          <span class="info-box-number">
            Jarak
            <small></small>
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </a>
  </div>
  @endif
  <!-- fix for small devices only -->
  <div class="clearfix hidden-md-up"></div>

  

  <!-- /.col -->
  <div class="col-12 col-sm-6 col-md-3">
    <a href="#" style="color:black">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-percent"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">PERSENTASE KEHADIRAN</span>
          <span class="info-box-number">
            @if ($persen_kehadiran == null)
            0 %
            @else
            {{$persen_kehadiran->persen_kehadiran}} %
            @endif

          </span>
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
          <span class="info-box-text"><i class="far fa-calendar"></i><br /><strong>PRESENSI MASUK</strong></span>
        </div>
      </div>
    </a>
  </div>
  <div class="col-lg-6 col-6">
    <a href="/pegawai/presensi/pulang">
      <div class="info-box bg-gradient-danger">
        <div class="info-box-content text-center">
          <span class="info-box-text"><i class="far fa-calendar"></i><br /><strong>PRESENSI PULANG</strong></span>
        </div>
      </div>
    </a>
  </div>
</div> --}}
{{-- OS : {{$os}} --}}
@endsection

@push('js')

@include('sweetalert::alert', ['cdn' => "https://cdn.jsdelivr.net/npm/sweetalert2@9"])

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
  integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
  crossorigin=""></script>

<script>
  navigator.geolocation.getCurrentPosition(function(location) {
      var latlng = new L.LatLng(location.coords.latitude, location.coords.longitude);

      var mymap = L.map('mapid').setView(latlng, 14);
      googleStreets = L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{
          maxZoom: 20,
          subdomains:['mt0','mt1','mt2','mt3']
      }).addTo(mymap);

      var latlng2 = {!!json_encode($latlong2)!!}
      
      var marker = L.marker(latlng).addTo(mymap).bindPopup('Lokasi Saya').openPopup();
      
      L.marker(latlng2).addTo(mymap).bindPopup('Lokasi Presensi').openPopup();
      
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
      
    });
    
</script>
@endpush