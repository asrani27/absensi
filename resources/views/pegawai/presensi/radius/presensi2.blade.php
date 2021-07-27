@extends('layouts.app')

@push('css')
    
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
crossorigin=""/>
<style>
    #mapid { height: 10px; }
</style>

<link rel="stylesheet" href="https://googlechrome.github.io/samples/styles/main.css">
<link rel="stylesheet" href="https://googlechrome.github.io/samples/image-capture/grab-frame-take-photo.css">
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
                <div class="form-group row">
                    
                    <video autoplay></video>
                    <button id='grabFrameButton'>Take Photo</button>
                    <canvas id='grabFrameCanvas'></canvas>
                </div>
                <form method="post" action="/pegawai/presensi/radius">
                    @csrf
                <input type="hidden" name="datajarak" id="datajarak">
                <div class="form-group row">
                    <div class="col-6 text-center">
                        <strong>{{$jam_masuk == null ? '00:00:00': $jam_masuk}}</strong>
                        <input type="hidden" id="photo" name="photo">
                    </div>
                    <div class="col-6 text-center">
                        <strong>{{$jam_pulang == null ? '00:00:00': $jam_pulang}}</strong>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-6">
                        <button type="submit" class="btn btn-block bg-gradient-success" name="button" value="masuk">MASUK</button>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn btn-block bg-gradient-danger" name="button" value="pulang">PULANG</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
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


  
    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>

var imageCapture;
$(document).ready(function(){
  navigator.mediaDevices.getUserMedia({video: true})
  .then(mediaStream => {
    document.querySelector('video').srcObject = mediaStream;

    const track = mediaStream.getVideoTracks()[0];
    imageCapture = new ImageCapture(track);
  })
  .catch(error => ChromeSamples.log(error));
});

// function onGetUserMediaButtonClick() {
//   navigator.mediaDevices.getUserMedia({video: true})
//   .then(mediaStream => {
//     document.querySelector('video').srcObject = mediaStream;

//     const track = mediaStream.getVideoTracks()[0];
//     imageCapture = new ImageCapture(track);
//   })
//   .catch(error => ChromeSamples.log(error));
// }

function onGrabFrameButtonClick() {
  imageCapture.grabFrame()
  .then(imageBitmap => {
    const canvas = document.querySelector('#grabFrameCanvas');
    drawCanvas(canvas, imageBitmap);
    var canvase = document.getElementById('grabFrameCanvas');
    var dataURL = canvase.toDataURL();
    document.getElementById("photo").value = dataURL;
    console.log(dataURL);
  })
  .catch(error => ChromeSamples.log(error));
}

function onTakePhotoButtonClick() {
  imageCapture.takePhoto()
  .then(blob => createImageBitmap(blob))
  .then(imageBitmap => {
    const canvas = document.querySelector('#takePhotoCanvas');
    drawCanvas(canvas, imageBitmap);
  })
  .catch(error => ChromeSamples.log(error));
  
}

/* Utils */

function drawCanvas(canvas, img) {
  canvas.width = getComputedStyle(canvas).width.split('px')[0];
  canvas.height = getComputedStyle(canvas).height.split('px')[0];
  let ratio  = Math.min(canvas.width / img.width, canvas.height / img.height);
  let x = (canvas.width - img.width * ratio) / 2;
  let y = (canvas.height - img.height * ratio) / 2;
  canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
  canvas.getContext('2d').drawImage(img, 0, 0, img.width, img.height,
      x, y, img.width * ratio, img.height * ratio);
}

document.querySelector('video').addEventListener('play', function() {
  document.querySelector('#grabFrameButton').disabled = false;
//  document.querySelector('#takePhotoButton').disabled = false;
});
</script>
<script>
    // document.querySelector('#getUserMediaButton').addEventListener('click', onGetUserMediaButtonClick);
    document.querySelector('#grabFrameButton').addEventListener('click', onGrabFrameButtonClick);
   // document.querySelector('#takePhotoButton').addEventListener('click', onTakePhotoButtonClick);
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
      console.log(distance,km);
    });
</script>
@endpush