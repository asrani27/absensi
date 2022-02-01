@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
CUTI/TL/IZIN/SAKIT
@endsection
@section('content')
<br />
<div class="row">
    <div class="col-12">
        <a href="/puskesmas/cuti/create" class="btn btn-sm btn-primary"><i class="fas fa-calendar"></i> Tambah
            Data</a><br /><br />
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Cuti</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>NIP/Nama</th>
                            @if (Auth::user()->username == '1.02.01.')
                            <th>Rs/Puskesmas</th>
                            @endif
                            <th>Tgl Mulai</th>
                            <th>Tgl Selesai</th>
                            <th>Jenis</th>
                            <th>Dokumen</th>
                            <th>Keterangan</th>
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
                            <td>{{$item->nama}}<br />{{$item->nip}}</td>
                            @if (Auth::user()->username == '1.02.01.')
                            <td>{{$item->puskesmas == null ? 'Dinas Kesehatan': $item->puskesmas->nama}}</td>
                            @endif
                            <td>{{\Carbon\Carbon::parse($item->tanggal_mulai)->isoFormat('D MMMM Y')}}</td>
                            <td>{{\Carbon\Carbon::parse($item->tanggal_selesai)->isoFormat('D MMMM Y')}}</td>
                            <td>{{$item->jenis_keterangan->keterangan}}</td>
                            <td>
                                @if ($item->file == null)
                                <a href="/puskesmas/cuti/upload/{{$item->id}}" class="btn btn-xs btn-info"
                                    target="_blank">Upload</a>
                                @else
                                <a href="/storage/cuti/{{$item->file}}" class="btn btn-xs btn-info"
                                    target="_blank">Lihat</a>
                                <a href="/puskesmas/cuti/upload/{{$item->id}}" class="btn btn-xs btn-success"
                                    target="_blank">Upload</a>
                                @endif
                            </td>
                            <td>{{$item->keterangan}}</td>
                            <td>

                                <a href="/puskesmas/cuti/{{$item->id}}/delete" class="btn btn-xs btn-danger"
                                    onclick="return confirm('yakin DI Hapus?');"><i class="fas fa-trash"></i> Delete</a>

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