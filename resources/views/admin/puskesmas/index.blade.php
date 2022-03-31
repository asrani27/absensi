@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
ADMIN
@endsection
@section('content')
<br />
<div class="row">
    <div class="col-12">
        <a href="/admin/puskesmas/sync" class="btn btn-sm bg-gradient-purple"><i class="fas fa-sync"></i> Sinkronisasi
            Puskemas</a>
        {{-- <a href="/admin/pegawai/sortir" class="btn btn-sm bg-gradient-info"><i class="fas fa-users"></i> Urutkan
            Pegawai</a> --}}
        <br /><br />
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data rs & Puskesmas</h3>
                <div class="card-tools">
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    @php
                    $no =1;
                    @endphp
                    <tbody>
                        @foreach ($data as $key => $item)
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif; ">
                            <td>{{$no++}}</td>
                            <td>1.02.01.{{$item->id}}</td>
                            <td>{{$item->nama}}</td>
                            <td>
                                @if ($item->user_id == null)
                                <a href="/admin/puskesmas/{{$item->id}}/createuser"
                                    class="btn btn-xs bg-gradient-success">Buat Akun</a>
                                @else
                                <a href="/admin/puskesmas/{{$item->id}}/resetpass" class="btn btn-xs btn-secondary"><i
                                        class="fas fa-key"></i> Reset Pass</a>
                                <a href="/admin/puskesmas/{{$item->id}}/gantipass" class="btn btn-xs btn-warning"><i
                                        class="fas fa-lock"></i> Ganti Pass</a>
                                <a href="/admin/puskesmas/{{$item->id}}/login" class="btn btn-xs bg-gradient-danger">
                                    Masuk <i class="fas fa-arrow-right"></i></a>

                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>

@endsection

@push('js')
@endpush