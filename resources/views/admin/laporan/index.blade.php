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
      Laporan presensi Per Tanggal
    </div>
    <div class="card-body">
      <form method="get" action="/admin/laporan/tanggal" target="_blank">
        @csrf
      <div class="row">
        <div class="col-sm-10">
            <div class="form-group">
                <input type="date" name="tanggal" class="form-control" value="{{\Carbon\Carbon::today()->format('Y-m-d')}}">
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <button type="submit" class="btn btn-danger">Print</button>
            </div>
        </div>
      </div>
      </form>
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

      <form method="get" action="/admin/laporan/rekap" target="_blank">
        @csrf
      <div class="row">
        <div class="col-sm-5">
            <div class="form-group">
              <select class="form-control" name="bulan" required>
                <option value="">-pilih bulan-</option>
                <option value="01" {{$bulan == '01' ? 'selected':''}}>Januari</option>
                <option value="02" {{$bulan == '02' ? 'selected':''}}>Februari</option>
                <option value="03" {{$bulan == '03' ? 'selected':''}}>Maret</option>
                <option value="04" {{$bulan == '04' ? 'selected':''}}>April</option>
                <option value="05" {{$bulan == '05' ? 'selected':''}}>Mei</option>
                <option value="06" {{$bulan == '06' ? 'selected':''}}>Juni</option>
                <option value="07" {{$bulan == '07' ? 'selected':''}}>Juli</option>
                <option value="08" {{$bulan == '08' ? 'selected':''}}>Agustus</option>
                <option value="09" {{$bulan == '09' ? 'selected':''}}>September</option>
                <option value="10" {{$bulan == '10' ? 'selected':''}}>Oktober</option>
                <option value="11" {{$bulan == '11' ? 'selected':''}}>November</option>
                <option value="12" {{$bulan == '12' ? 'selected':''}}>Desember</option>
              </select>
            </div>
        </div>
        
        <div class="col-sm-5">
          <div class="form-group">
            <select class="form-control" name="tahun" required>
              <option value="">-pilih tahun-</option>
              <option value="2021" {{$tahun == '2021' ? 'selected':''}}>2021</option>
              <option value="2022" {{$tahun == '2022' ? 'selected':''}}>2022</option>
              <option value="2023" {{$tahun == '2023' ? 'selected':''}}>2023</option>
            </select>
          </div>
      </div>
        <div class="col-sm-2">
            <div class="form-group">
                <button type="submit" name="button" value="1" class="btn btn-danger">Print</button>
                <button type="submit" name="button" value="2" class="btn btn-warning">Tampilkan</button>
            </div>
        </div>
      </div>
      </form>

      <table class="table table-hover table-striped table-bordered text-nowrap table-sm  table-responsive">
        <thead>
            <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif" class="bg-gradient-primary">
            <th rowspan=2>No</th>
            <th rowspan=2>NIP</th>
            <th rowspan=2>Nama</th>
            <th rowspan=2>Jabatan</th>
            <th rowspan=2>Jum Hari</th>
            <th colspan=2 class="text-center">Hadir Di Hari</th>
            <th colspan=8 class="text-center">Ketidakhadiran*</th>
            <th colspan=2 >Total Absensi</th>
            <th rowspan=2>Jam Kerja Pegawai</th>
            <th rowspan=2>Datang Lambat</th>
            <th rowspan=2>Pulang Cepat</th>
            <th rowspan=2>% Hadir</th>
            <th rowspan=2>Total Hari Kerja</th>
            </tr>
            
            <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif" class="bg-gradient-primary">
            <th>Kerja</th>
            <th>Libur</th>
            <th>A</th>
            <th>S</th>
            <th>TR</th>
            <th>D</th>
            <th>I</th>
            <th>C</th>
            <th>L</th>
            <th>O</th>
            <th>Masuk</th>
            <th>Keluar</th>
            </tr>
        </thead>
        @php
            $no =1;
        @endphp
        <tbody>
        @foreach ($data as $key => $item)
                <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif">
                <td>{{$no++}}</td>
                <td>{{$item->nip}}</td>
                <td>{{$item->nama}}</td>
                <td>{{$item->jabatan}}</td>
                <td>0</td>              
                <td>0</td>              
                <td>0</td>              
                <td>0</td>              
                <td>0</td>              
                <td>0</td>              
                <td>0</td>              
                <td>0</td>              
                <td>0</td>              
                <td>0</td>              
                <td>0</td>              
                <td>0</td>              
                <td>0</td>              
                <td>0</td>              
                <td>0</td>              
                <td>0</td>              
                <td>100</td>           
                <td>0</td>              
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