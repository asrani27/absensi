@extends('layouts.app')

@push('css')
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
    RAMADHAN
@endsection
@section('content')
<br/>
<div class="row">
    <div class="col-12">
        <a href="/superadmin/ramadhan/create" class="btn btn-sm btn-primary"><i class="fa fa-calendar"></i> Tambah Tanggal Ramadhan</a><br/><br/>
        <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Tanggal Ramadhan</h3>
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
                    <td>{{\Carbon\Carbon::parse($item->tanggal)->format('d M Y')}}</td>
                    <td>
                        
                    <form action="/superadmin/ramadhan/{{$item->id}}" method="post">
                        {{-- <a href="/superadmin/ramadhan/{{$item->id}}/edit" class="btn btn-xs btn-success"><i class="fas fa-edit"></i> Edit</a> --}}
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
        {{$data->links()}}
    </div>
</div>

@endsection

@push('js')
@endpush