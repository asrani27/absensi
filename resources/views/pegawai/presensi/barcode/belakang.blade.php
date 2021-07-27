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
@endpush
@section('title')
  <strong>PRESENSI</strong>
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body text-center">
              @if (strtotime('now') < strtotime('12:00pm'))
                  <span class="badge badge-warning">Anda Berada Di Scan Masuk</span>
              @else
                  <span class="badge badge-warning">Anda Berada Di Scan Pulang</span>
              @endif
              <video id="video" width="100%" height="200" playsinline></video>
            </div>
        </div>
    </div>
</div>
        
@endsection 

@push('js')

<script type="text/javascript">
    
    scanner = new Instascan.Scanner({ video: video, scanPeriod: 4, mirror:false })
                .then(handleSuccess)
                .catch(handleError);
             //Start scanning
             scanner.addListener('scan', foundCode);

             Instascan.Camera.getCameras().then(function (cameras) {
                 if (cameras.length > 0) {
                     scanner.start(cameras[0]);
                 }
                 else {
            
                 }
             }).catch (function (e) {
             
             });
  </script>
@endpush