@extends('layouts.app')

@push('css')
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
    ADMIN
@endsection
@section('content')
<br/>
<div class="row">
    Gunakan Ctrl + F Untuk Pencarian Pegawai
    <div class="col-12">
        <div class="card">
        <div class="card-header">
            <h3 class="card-title">Urutkan Data Pegawai</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap table-sm">
            <thead>
                <tr>
                <th>#</th>
                <th>NIP/Nama/Jabatan</th>
                <th>No Urut</th>
                </tr>
            </thead>
            @php
                $no =1;
            @endphp
            <form method="post" action="/admin/pegawai/sortir">
                @csrf
            <tbody>
            @foreach ($data as $key => $item)
                <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif">
                <td>{{$no++}}</td>
                <td>{{$item->nama}}<br/>{{$item->nip}}<br/>{{$item->jabatan}}</td>
                <td>
                    <input type="hidden" name="pegawai_id[]" value="{{$item->id}}">
                    <input type="text" name="urutan[]" class="form-control" size="1" maxlength="4" onkeypress="return hanyaAngka(event)" value="{{$item->urutan}}">
                </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3">
                    <button type="submit" class="btn btn-lg btn-block btn-info">SIMPAN</button>
                </td>
            </tr>
            </tbody>
            </form>
            </table>
        </div>
        <!-- /.card-body -->
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    function hanyaAngka(evt) {
      var charCode = (evt.which) ? evt.which : event.keyCode
       if (charCode > 31 && (charCode < 48 || charCode > 57))

        return false;
      return true;
    }
</script>
@endpush