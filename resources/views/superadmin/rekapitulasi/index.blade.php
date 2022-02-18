@extends('layouts.app')

@push('css')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
  integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
  crossorigin="" />
<style>
  #mapid {
    height: 380px;
  }
</style>
@endpush
@section('title')
<strong>PRESENSI</strong>
@endsection
@section('content')
<br />
<div class="row">
  <div class="col-lg-12">
    <div class="card card-widget">
      <div class="card-header">
        <div class="user-block">
          <img class="img-circle"
            src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/User_icon_2.svg/2048px-User_icon_2.svg.png"
            alt="User Image">
          <span class="username"><a href="#">{{Auth::user()->name}}</a></span>
          <span class="description">SELAMAT DATANG DI APLIKASI ADMIN PRESENSI</span>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        Laporan presensi Per Bulan
      </div>
      <div class="card-body">
        <table class="table table-hover table-striped text-nowrap table-sm">
          <thead>
            <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif" class="bg-gradient-primary">
              <th>#</th>
              <th>Bulan</th>
              <th>Tahun</th>
              <th>Aksi</th>
            </tr>
          </thead>
          @php
          $no =1;
          @endphp
          <tbody>

            @foreach (bulanTahun() as $key => $item)
            <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif">
              <td>{{$no++}}</td>
              <td>{{\Carbon\Carbon::createFromFormat('m',$item->bulan)->translatedFormat('F')}}</td>
              <td>{{$item->tahun}}</td>
              <td><a href="/superadmin/rekapitulasi/{{$item->bulan}}/{{$item->tahun}}" class="btn btn-xs btn-success"><i
                    class="fas fa-eye"></i> Detail</a></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@endsection

@push('js')

@endpush