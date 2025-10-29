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
  <div class="col-lg-12 col-md-12">
    <form method="post" action="/mod/absensi">
      @csrf
      <div class="form-group">
        <label>Cari Nama</label>
        <select class="form-control select2-pegawai" name="nip" required>
          <option value="">-search-</option>
          @php
          $selectedNip = old('nip');
          $selectedPegawai = null;
          if($selectedNip) {
          $selectedPegawai = \App\Models\Pegawai::where('nip', $selectedNip)->first();
          }
          @endphp
          @if($selectedPegawai)
          <option value="{{$selectedPegawai->nip}}" selected>{{$selectedPegawai->nip}} - {{$selectedPegawai->nama}}
          </option>
          @endif
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
      <div class="form-group">
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-success btn-block" name="button" value="apel">PRESENSI APEL</button>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-warning btn-block" name="button" value="haribesar">PRESENSI HARI
              BESAR</button>
          </div>
        </div>
      </div>
    </form>
  </div>

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

    //Initialize Select2 with AJAX for pegawai search
    $('.select2-pegawai').select2({
      theme: 'bootstrap4',
      minimumInputLength: 2, // Minimum 2 characters to trigger search
      ajax: {
        url: '/mod/search-pegawai',
        dataType: 'json',
        delay: 250, // Delay in milliseconds to avoid too many requests
        data: function (params) {
          return {
            q: params.term // search term
          };
        },
        processResults: function (data) {
          return {
            results: data
          };
        },
        cache: true
      },
      placeholder: '-search-',
      allowClear: true
    });

    // Trigger change event to ensure Select2 recognizes the selected option
    $('.select2-pegawai').trigger('change');
  })
</script>
@endpush