@extends('layouts.app')

@push('css')

<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- Select2 -->
<link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

@endpush
@section('title')
    SETUP LOKASI
@endsection
@section('content')
<br/>
<div class="row">
    <div class="col-12">
        <a href="/admin/pegawai" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a><br/><br/>
        <form method="post" action="/admin/pegawai/{{$data->id}}/editlokasi">
            @csrf
            <div class="row">
                <div class="col-lg-12 col-12">
                    <div class="card">
                        <div class="card-body">
                            
                            <div class="form-group row">
                            <label class="col-sm-2 col-form-label">NIP</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nip" required readonly value="{{$data->nip}}">
                            </div>
                            </div>

                            <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nama" required readonly value="{{$data->nama}}">
                            </div>
                            </div>
                            
                            <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Lokasi Presensi</label>
                            <div class="col-sm-10">
                                <select class="form-control select2" name="lokasi_id" style="width: 100%;">
                                    @foreach ($lokasi as $item)
                                        <option value="{{$item->id}}" {{$data->lokasi_id == $item->id ? 'selected':''}}>{{$item->nama}} - {{$item->alamat}}</option>
                                    @endforeach
                                </select>
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
        </form>
    </div>
</div>

@endsection

@push('js')
<!-- Select2 -->
<script src="/theme/plugins/select2/js/select2.full.min.js"></script>

<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2({
      theme: 'bootstrap4',
      placeholder: 'Pilih Lokasi Presensi',
      allowClear: true
    })
  })
</script>
@endpush
