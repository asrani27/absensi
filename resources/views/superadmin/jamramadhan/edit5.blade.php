@extends('layouts.app')

@push('css')
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
    EDIT JAM
@endsection
@section('content')
<br/>
<div class="row">
    <div class="col-12">
        <a href="/superadmin/jamramadhan" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a><br/><br/>
<form method="post" action="/superadmin/jam5ramadhan/{{$data->id}}">
    @csrf
    
    <div class="row">
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Hari</label>
                    <div class="col-sm-10" required>
                        <select name="hari" class="form-control" readonly>
                            <option value="senin" {{$data->hari == 'senin' ? 'selected':''}}>SENIN</option>
                            <option value="selasa" {{$data->hari == 'selasa' ? 'selected':''}}>SELASA</option>
                            <option value="rabu" {{$data->hari == 'rabu' ? 'selected':''}}>RABU</option>
                            <option value="kamis" {{$data->hari == 'kamis' ? 'selected':''}}>KAMIS</option>
                            <option value="jumat" {{$data->hari == 'jumat' ? 'selected':''}}>JUMAT</option>
                            <option value="sabtu" {{$data->hari == 'sabtu' ? 'selected':''}}>SABTU</option>
                            <option value="minggu" {{$data->hari == 'minggu' ? 'selected':''}}>MINGGU</option>
                        </select>
                    </div>
                    </div>
                    
                    <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Jam Masuk</label>
                    <div class="col-sm-10">
                        <input type="time" class="form-control" name="jam_masuk" value="{{$data->jam_masuk}}" required>
                    </div>
                    </div>

                    <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Jam Pulang</label>
                    <div class="col-sm-10">
                        <input type="time" class="form-control" name="jam_pulang" value="{{$data->jam_pulang}}" required>
                    </div>
                    </div>

                    <div class="form-group row">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-block btn-primary"><strong>UPDATE JAM</strong></button>
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