@extends('layouts.app')

@push('css')
    
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
                  
                </ul>
            </div>    
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="/pegawai/presensi/manual">
                    @csrf
                    <div class="form-group row">
                        <div class="custom-file">
                        <input type="file" class="custom-file-input" id="customFile">
                        <label class="custom-file-label" for="customFile">Dokumen Pendukung</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Keterangan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="keterangan" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <button type="submit" class="btn btn-block bg-gradient-success" name="button" value="masuk">KIRIM KE ADMIN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>        
@endsection 

@push('js')

@endpush