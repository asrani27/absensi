@extends('layouts.app')

@push('css')
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
    TAMBAH JAM
@endsection
@section('content')
<br/>
<div class="row">
    <div class="col-12">
        <a href="/superadmin/jam" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a><br/><br/>
<form method="post" action="/superadmin/jam">
    @csrf
    <div class="row">
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Hari</label>
                    <div class="col-sm-10" required>
                        <select name="hari" class="form-control">
                            <option value="senin">SENIN</option>
                            <option value="selasa">SELASA</option>
                            <option value="rabu">RABU</option>
                            <option value="kamis">KAMIS</option>
                            <option value="jumat">JUMAT</option>
                            <option value="sabtu">SABTU</option>
                            <option value="minggu">MINGGU</option>
                        </select>
                    </div>
                    </div>
                    
                    <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Jam Masuk</label>
                    <div class="col-sm-10">
                        <input type="time" class="form-control" name="jam_masuk" required>
                    </div>
                    </div>

                    <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Jam Pulang</label>
                    <div class="col-sm-10">
                        <input type="time" class="form-control" name="jam_pulang" required>
                    </div>
                    </div>

                    <div class="form-group row">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-block btn-primary"><strong>SIMPAN JAM</strong></button>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
    </div>
</div>

@endsection

@push('js')

@endpush