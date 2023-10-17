@extends('layouts.app')

@push('css')


  <!-- Select2 -->
  <link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
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
          <img class="img-circle"
            src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/User_icon_2.svg/2048px-User_icon_2.svg.png"
            alt="User Image">
          <span class="username"><a href="#">{{Auth::user()->name}}</a></span>
          <span class="description">Akun Mod</span>
        </div>
      </div>
      <div class="card-footer bg-white p-0">
        
      </div>
    </div>
  </div>
</div>
<div class="row">
  <form method="post" action="/mod/absensi">
  @csrf
  <div class="col-12 col-md-12">
    <div class="form-group">
      <label>Cari Nama</label>
      <select class="form-control select2" name="nip" required>
        <option value="">-search-</option>
        @foreach ($pegawai as $item)
        <option value="{{$item->nip}}" {{old('nip') == $item->nip ? 'selected':''}}>{{$item->nip}} - {{$item->nama}}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label>Tanggal</label>
      <input type="date" class="form-control" name='tanggal' value="{{old('tanggal')}}" required>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-6">
        <button type="submit" class="btn btn-primary btn-block" name="button" value="masuk">MASUK</button>
        </div>
        <div class="col-6">
        <button type="submit" class="btn btn-danger btn-block" name="button" value="pulang">PULANG</button>
        </div>
      </div>
    </div>
  </div>
  
  </form>
</div>

@endsection

@push('js')

@include('sweetalert::alert', ['cdn' => "https://cdn.jsdelivr.net/npm/sweetalert2@9"])

<!-- Select2 -->
<script src="/theme/plugins/select2/js/select2.full.min.js"></script>

<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  })
</script>
@endpush