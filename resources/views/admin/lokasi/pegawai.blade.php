@extends('layouts.app')

@push('css')
<!-- Select2 -->
<link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
LOKASI PEGAWAI
@endsection
@section('content')
<br />
<div class="row">
    <div class="col-12">
        <a href="/admin/lokasi" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i>
            Kembali</a><br /><br />

        <div class="row">
            <div class="col-md-6">
                <!-- general form elements -->
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Info Lokasi</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nama Lokasi</label>
                            <input type="text" class="form-control" value="{{$data->nama}}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Latitude</label>
                            <input type="text" class="form-control" value="{{$data->lat}}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Longitude</label>
                            <input type="text" class="form-control" value="{{$data->long}}" readonly>
                        </div>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Nama</th>
                                    <th style="width: 40px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $no =1;
                                @endphp
                                @foreach ($data->pegawailokasi as $item)
                                <tr style="font-size: 12px;">
                                    <td>{{$no++}}</td>
                                    <td>{{$item->nama}}<br />{{$item->nip}}</td>
                                    <td>
                                        <a href="/admin/lokasi/{{$id}}/pegawai/hapuslokasi/{{$item->id}}"
                                            class="btn btn-xs btn-danger">Hapus</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="col-md-6">
                <!-- general form elements -->
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Masukkan Semua Pegawai</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form role="form">
                        <div class="card-body">
                            <div class="form-group">
                                <a href="/admin/lokasi/{{$id}}/pegawai/masukkan"
                                    class="btn btn-primary btn-block">Masukkan Semua Pegawai</a>

                                <a href="/admin/lokasi/{{$id}}/pegawai/reset" class="btn btn-danger btn-block">Reset
                                    Pegawai</a>
                            </div>
                            {{-- <div class="form-group">
                                <label>Pegawai</label>
                                <select name="pegawai_id" class="form-control select2">
                                    <option value="">-pilih-</option>
                                    @foreach ($pegawai as $item)
                                    <option value="{{$item->id}}">{{$item->nip}} - {{$item->nama}}</option>
                                    @endforeach
                                </select>
                                <br />
                                <a href="#" class="btn btn-primary btn-block">Simpan</a>
                            </div> --}}
                        </div>
                    </form>
                </div>

                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Masukkan Per Satu Pegawai</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form role="form" action="/admin/lokasi/{{$id}}/pegawai" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label>NIP Pegawai</label>
                                <input type="text" class="form-control" name='nip'>
                                <br />
                                <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Masukkan Per Puskesmas</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form role="form" action="/admin/lokasi/{{$id}}/puskesmas" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label>Puskesmas</label>
                                <select name="puskesmas_id" class="form-control">
                                    @foreach ($puskesmas as $item)
                                    <option value="{{$item->id}}">{{$item->nama}}</option>
                                    @endforeach
                                </select>
                                <br />
                                <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="/theme/plugins/select2/js/select2.full.min.js"></script>

<script>
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
</script>
@endpush