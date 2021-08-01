@extends('layouts.app')

@push('css')
    
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
crossorigin=""/>
<style>
    #mapid { height: 380px; }
</style>
@endpush
@section('title')
  <strong>PRESENSI</strong>
@endsection
@section('content')
<br/>
<div class="row">
    <div class="col-lg-12">
        <div class="card card-widget">
            <div class="card-header">
                <div class="user-block">
                  <img class="img-circle" src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/User_icon_2.svg/2048px-User_icon_2.svg.png" alt="User Image">
                  <span class="username"><a href="#">{{Auth::user()->name}}</a></span>
                  <span class="description">SELAMAT DATANG DI APLIKASI ADMIN PRESENSI, HARAP SETTING LAT DAN LONG</span>
                </div>
              </div>    
        </div>
    </div>
</div>       

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                Data Presensi 
            </div>    
            <div class="card-body">
                <div class="row">
                <div class="col-sm-10">
                    <div class="form-group">
                        <input type="date" class="form-control" id="inputSuccess" value="{{\Carbon\Carbon::today()->format('Y-m-d')}}">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Tampilkan</button>
                        <a href="/admin/generate/presensi" class="btn btn-warning">Generate</a>
                    </div>
                </div>
                </div>

                
            <table class="table table-hover text-nowrap table-sm">
                <thead>
                    <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif" class="bg-gradient-primary">
                    <th>#</th>
                    <th>Nama/NIP</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                    </tr>
                </thead>
                @php
                    $no =1;
                @endphp
                <tbody>
                @foreach ($data as $key => $item)
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif">
                        <td>{{$no++}}</td>
                        <td>{{$item->nama}}<br/>{{$item->nip}}</td>
                        <td>{{$item->jam_masuk == null ? '00:00:00': $item->jam_masuk}}</td>
                        <td>{{$item->jam_pulang == null ? '00:00:00': $item->jam_pulang}}</td>
                        <td>{{$item->keterangan}}</td>   
                        <td>
                            @if ($item->keterangan != null)    
                            <a href="#" class="btn btn-xs btn-info">Presensi</a>
                            @endif
                        </td>                 
                        </tr>
                    @endforeach
                </tbody>
                </table>
            </div>    
        </div>
    </div>
</div>       
{{-- <div id="mapid"></div>
<form method="post" action="/admin/updatelocation">
    @csrf
    <div class="row">
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Latitude</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="lat" name="lat" value="{{$lat}}">
                    </div>
                    </div>
                    
                    <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Longitude</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="long"  name="long"  value="{{$long}}">
                    </div>
                    </div>
                    
                    <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Radius Jangkauan</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="radius" placeholder="100 meter" value="{{$radius}}">
                    </div>
                    </div>

                    <div class="form-group row">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-block btn-primary"><strong>UPDATE LOKASI PRESENSI</strong></button>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form> --}}
@endsection

@push('js')
{{-- <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
crossorigin=""></script>


<script>
    var latlng = {!!json_encode($latlong)!!}
    
    var map = L.map('mapid').setView(latlng, 16);
    googleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{
        maxZoom: 20,
        subdomains:['mt0','mt1','mt2','mt3']
    }).addTo(map);
  
    L.marker([latlng.lat,latlng.lng]).addTo(map);  

    var theMarker = {};
    console.log(latlng);
    map.on('click', function(e) {
        
        document.getElementById("lat").value = e.latlng.lat;
        document.getElementById("long").value = e.latlng.lng;
        
        if (theMarker != undefined) {
            map.removeLayer(theMarker);
        };
        
        theMarker = L.marker([e.latlng.lat,e.latlng.lng]).addTo(map);  
    });
    
</script> --}}
@endpush