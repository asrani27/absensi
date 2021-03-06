@extends('layouts.app')

@push('css')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
    integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
    crossorigin="" />
<style>
    #mapid {
        height: 400px;
    }
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
                            <span class="float-right text-danger">{{\Carbon\Carbon::now()->isoFormat('D MMMM Y
                                HH:mm')}}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            Lokasi Presensi
                            <span class="float-right text-success">{{Auth::user()->pegawai->lokasi == null ?
                                '-':Auth::user()->pegawai->lokasi->nama}}</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            Jenis Presensi
                            <span class="float-right text-primary">Shift</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            Presensi
                            <span class="float-right text-primary">Malam</span>
                        </a>
                    </li>

                    {{-- <li class="nav-item">
                        <a href="#" class="nav-link">Jarak
                            <span class="float-right text-primary">
                                <div id="jarak"></div>
                            </span>
                        </a>
                    </li> --}}
                    <li class="nav-item">
                        <a href="/pegawai/presensi/malam" class='btn btn-xs btn-primary btn-block'><i
                                class="fas fa-sync"></i> Dapatkan Lokasi Saya</a>
                        {{-- <a href="/home/pegawai" class='btn btn-xs btn-secondary btn-block'><i class=""></i>
                            Kembali</a> --}}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Presensi Shift Malam</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 10px">Tanggal</th>
                            <th class="text-center">Masuk</th>
                            <th class="text-center">Pulang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                        <tr>
                            <td class="text-center">{{\Carbon\Carbon::parse($item->tanggal)->format('d')}}</td>
                            <td class="text-center">
                                @if ($item->shift_jam_masuk == null)
                                <a href="/pegawai/presensi/malam/masuk/{{$item->id}}"
                                    class="btn btn-xs bg-gradient-success"
                                    onclick="return confirm('Konfirmasi Sekali lagi, yakin?');">MASUK</a>
                                @else
                                {{$item->shift_jam_masuk}}
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($item->shift_jam_pulang == null)
                                <a href="/pegawai/presensi/malam/pulang/{{$item->id}}"
                                    class="btn btn-xs bg-gradient-success"
                                    onclick="return confirm('Konfirmasi Sekali lagi, yakin?');">PULANG</a>
                                @else
                                {{$item->shift_jam_pulang}}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <div class="card">
            <div class="card-body">

                {{-- <form id="radius" method="post" action="/pegawai/presensi/malam">
                    @csrf
                    <input type="hidden" name="datajarak" id="datajarak">
                    <div class="form-group row">
                        <div class="col-6 text-center">

                            <strong>Masuk<br />{{$jam_masuk == null ? '00:00:00':
                                \Carbon\Carbon::parse($jam_masuk)->format('d-M-Y H:i:s')}}</strong>
                            {{-- <input type="hidden" id="photo" name="photo">
                            <input type="hidden" id="button" name="button">
                        </div>
                        <div class="col-6 text-center">
                            <strong>Pulang<br />{{$jam_pulang == null ? '00:00:00':
                                \Carbon\Carbon::parse($jam_pulang)->format('d-M-Y H:i:s')}}</strong>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-block bg-gradient-success btnMasuk"
                                name="button">SIMPAN
                                PRESENSI</button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12" id="btnLoading">
                            <button type="button" class="btn btn-block bg-gradient-primary btnLoading"><i
                                    class="fas fa-sync-alt fa-spin"></i> Menyimpan...</button>
                        </div>
                    </div>
                </form> --}}
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
                    <span class="info-box-text"><i class="far fa-calendar"></i><br /><strong>PRESENSI
                            MASUK</strong></span>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-6 col-6">
        <a href="/pegawai/presensi/pulang">
            <div class="info-box bg-gradient-danger">
                <div class="info-box-content text-center">
                    <span class="info-box-text"><i class="far fa-calendar"></i><br /><strong>PRESENSI
                            PULANG</strong></span>
                </div>
            </div>
        </a>
    </div>
</div> --}}

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
      console.log(distance,km,latlng2);
    });
</script>
@endpush