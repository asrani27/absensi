@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
CUTI
@endsection
@section('content')
<br />
<div class="row">
    <div class="col-12">
        {{-- <a href="/superadmin/jam/create" class="btn btn-sm btn-primary"><i class="fa fa-clock"></i> Tambah
            Jam</a><br /><br /> --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Cuti {{$data->total()}}</h3>
                <div class="card-tools">
                    <form method="get" action="/superadmin/cuti/search">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="search" class="form-control float-right" value="{{old('search')}}"
                                placeholder="Nama / NIP">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama/Nip</th>
                            <th>Mulai - Selesai</th>
                            <th>Ket</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    @php
                    $no =1;
                    @endphp
                    <tbody>
                        @foreach ($data as $key => $item)
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif">
                            <td>{{$no++}}</td>
                            <td>{{$item->nip}}<br />
                                {{$item->nama}}</td>
                            <td>{{\Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y')}} s/d
                                {{\Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y')}}</td>
                            <td>{{$item->jenis_keterangan->keterangan}}</td>
                            <td>{{$item->created_at}}</td>
                            <td>

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