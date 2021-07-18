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
    <div class="col-12">
        <a href="/admin/pegawai/sync" class="btn btn-sm bg-gradient-purple"><i class="fas fa-sync"></i> Sinkronisasi Data</a>
        <a href="/admin/pegawai/createuser" class="btn btn-sm bg-gradient-green"><i class="fas fa-key"></i> Buat Akun Login</a>
        <br/><br/>
        <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Pegawai Presensi</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap table-sm">
            <thead>
                <tr>
                <th>#</th>
                <th>NIP</th>
                <th>Nama</th>
                <th>Tgl Lahir</th>
                <th>Lokasi Presensi</th>
                <th>Aksi</th>
                </tr>
            </thead>
            @php
                $no =1;
            @endphp
            <tbody>
            @foreach ($data as $key => $item)
                    <tr>
                    <td>{{$no++}}</td>
                    <td>{{$item->nip}}</td>
                    <td>{{$item->nama}}</td>
                    <td>{{\Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y')}}</td>
                    <td>
                        @if ($item->lokasi == null)
                        <a href="/admin/pegawai/{{$item->id}}/lokasi" class="btn btn-xs bg-gradient-primary"><i class="fas fa-pen"></i></a>
                        @else
                            {{$item->lokasi->nama}}
                            <a href="/admin/pegawai/{{$item->id}}/editlokasi" class="btn btn-xs bg-gradient-success"><i class="fas fa-pen"></i></a>
                        @endif
                    </td>
                    <td>
                        @if ($item->user == null)
                        <a href="/admin/pegawai/{{$item->id}}/createuser" class="btn btn-xs bg-gradient-success">Buat Akun</a>
                        @else
                            <a href="/admin/pegawai/{{$item->id}}/resetpass" class="btn btn-xs bg-gradient-secondary"><i class="fas fa-key"></i> Reset Password</a>
                        @endif
                    </td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        </div>
        {{$data->links()}}
    </div>
</div>

@endsection

@push('js')
@endpush