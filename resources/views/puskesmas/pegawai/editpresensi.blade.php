@extends('layouts.app')

@push('css')
<!-- Timepicker -->
<script src="/theme/plugins/moment/moment.min.js"></script>
<link rel="stylesheet" href="/theme/plugins/daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="/theme/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">

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
                    <span class="username"><a href="#">{{$data->nama}}</a></span>
                    <span class="description">{{$data->nip}}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                Edit Data Presensi
            </div>
            <form method="post" action="/puskesmas/pegawai/{{$id}}/presensi/{{$bulan}}/{{$tahun}}/{{$data->id}}/edit">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jam Mulai</label>
                        
                        <div class="col-sm-2">
                            <div class="input-group date" id="timepicker" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#timepicker"
                                    name="jam_masuk"
                                    value="{{$data->jam_masuk == null ? '00:00:00' : $data->jam_masuk}}" step="2">
                                <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="far fa-clock"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jam Pulang</label>

                        <div class="col-sm-2">
                            <div class="input-group date" id="timepicker2" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#timepicker2"
                                    name="jam_pulang"
                                    value="{{$data->jam_pulang == null ? '00:00:00' : $data->jam_pulang}}" step="2">
                                <div class="input-group-append" data-target="#timepicker2" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="far fa-clock"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-block btn-primary">Update</button>
                            <a href="/admin/pegawai/{{$id}}/presensi/{{$bulan}}/{{$tahun}}"
                                class="btn btn-block btn-secondary">Kembali</a>
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

<!-- Timepicker -->
<script src="/theme/plugins/moment/moment.min.js"></script>
<script src="/theme/plugins/daterangepicker/daterangepicker.js"></script>
<script src="/theme/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script>
    //Timepicker
    $('#timepicker').datetimepicker({
      format: 'HH:mm:ss',
      //format: 'hh:mm',
    })
    $('#timepicker2').datetimepicker({
      format: 'HH:mm:ss',
      //format: 'hh:mm',
    })
</script>
@endpush