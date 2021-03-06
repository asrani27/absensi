@extends('layouts.app')

@push('css')
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
    QR CODE
@endsection
@section('content')
<br/>
<div class="row">
    <div class="col-12">
        <a href="/admin/qrcode/generate" class="btn btn-sm bg-gradient-purple"><i class="fa fa-qrcode"></i> Generate QrCode</a><br/><br/>
        
        {{-- {!! QrCode::size(250)->generate('absensi.banjarmasinkota.go.id'); !!} --}}
        <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data QR Code</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap table-sm">
            <thead>
                <tr>
                <th>#</th>
                <th>Tanggal</th>
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
                <td>{{\Carbon\Carbon::parse($item->tanggal)->isoFormat('D MMMM Y')}}</td>
                <td>
                    <a href="/admin/qrcode/tampil/{{$item->id}}" class="btn btn-sm btn-info" target="_blank">Tampilkan QR</a>
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