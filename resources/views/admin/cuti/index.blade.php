@extends('layouts.app')

@push('css')
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
    CUTI/TL/IZIN/SAKIT
@endsection
@section('content')
<br/>
<div class="row">
    <div class="col-12">
        <a href="/admin/cuti/create" class="btn btn-sm btn-primary"><i class="fas fa-calendar"></i> Tambah Data</a><br/><br/>
        <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Absensi</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap table-sm">
            <thead>
                <tr>
                <th>#</th>
                <th>NIP</th>
                <th>Nama</th>
                <th>Tgl Mulai</th>
                <th>Tgl Selesai</th>
                <th>Keterangan</th>
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
                    <td>{{\Carbon\Carbon::parse($item->tanggal_mulai)->isoFormat('D MMMM Y')}}</td>
                    <td>{{\Carbon\Carbon::parse($item->tanggal_selesai)->isoFormat('D MMMM Y')}}</td>
                    <td>{{$item->keterangan}}</td>
                    <td>
                        
                    <form action="/admin/cuti/{{$item->id}}" method="post">
                        <a href="/admin/cuti/{{$item->id}}/edit" class="btn btn-xs btn-success"><i class="fas fa-edit"></i> Edit</a>
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('yakin DI Hapus?');"><i class="fas fa-trash"></i> Delete</button>     
                    </form>

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