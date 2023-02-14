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
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">History Perubahan Data</h3>
                <div class="card-tools">
                    <form method="get" action="/admin/perubahandata/search">
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
                            <th>Nip/Nama</th>
                            <th>Tanggal</th>
                            <th>Data Sebelumnya</th>
                            <th>Data Perubahan</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th>Verifikator</th>
                        </tr>
                    </thead>
                    @php
                    $no =1;
                    @endphp
                    <tbody>
                        @foreach ($data as $key => $item)
                        @if ($item->status == 0)
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif; background-color:#f2dede">
                            @else
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif; ">
                            @endif
                            <td>{{$key + $data->firstitem()}}</td>
                            <td>{{$item->nip}}<br/>{{namaByNip($item->nip)->nama}}</td>
                            <td>{{\Carbon\Carbon::parse($item->tanggal)->format('d-m-Y')}}</td>
                            <td>
                                masuk : {{\Carbon\Carbon::parse($item->masuk)->format('H:i:s')}}<br />
                                pulang : {{\Carbon\Carbon::parse($item->pulang)->format('H:i:s')}}
                            </td>
                            <td>
                                masuk : {{\Carbon\Carbon::parse($item->p_masuk)->format('H:i:s')}}<br />
                                pulang : {{\Carbon\Carbon::parse($item->p_pulang)->format('H:i:s')}}
                            </td>
                            <td>
                                Keterangan : {{$item->keterangan}}<br/>
                                Data Dukung : <a href="/storage/perubahan/{{$item->file}}" target="_blank">Lihat</a>
                            </td>
                            <td>
                                @if ($item->status == 0)
                                    di proses
                                @elseif($item->status == 1)
                                    di setujui
                                @else
                                    di tolak   
                                @endif
                            </td>
                            <td>{{$item->verifikator}}<br/>{{namaByNip($item->verifikator)->nama}}</td>
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