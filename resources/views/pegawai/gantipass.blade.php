@extends('layouts.app')

@push('css')

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
                  <span class="description">{{Auth::user()->username}}</span>
                </div>
            </div>    
        </div>
    </div>
</div>       

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                Ganti Pass
            </div>
            <form method="post" action="/pegawai/gantipass">
                @csrf   
            <div class="card-body">         
                <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Masukkan Password Lama</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="passlama" required>
                </div>
                </div>
                <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Masukkan Password Baru</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="passbaru" required>
                </div>
                </div>
                <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-block btn-primary">Simpan</button>
                </div>
                </div>
            </div>
            </form>
            </div>    
        </div>
    </div>
</div>       

@endsection

@push('js')

@endpush