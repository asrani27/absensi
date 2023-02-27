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
                <h3 class="card-title">Permohonan Perubahan Data</h3>
                <div class="card-tools">
                    
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
                            <th>Status</th>
                            <th>Aksi</th>
                            
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
                            <td>
                                <a href="/pegawai/presensi/verifikator/{{$item->id}}/setujui" onclick="return confirm('Yakin Ingin disetujui?');" class="btn btn-sm btn-success">setujui</a>
                                <a href="/pegawai/presensi/verifikator/{{$item->id}}/tolak" onclick="return confirm('Yakin Ingin ditolak?');" class="btn btn-sm btn-danger">tolak</a>
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