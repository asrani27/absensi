@extends('layouts.app')

@push('css')
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
    SETTING JAM
@endsection
@section('content')
<br/>
<div class="row">
    <div class="col-12">
        <a href="/superadmin/jam/create" class="btn btn-sm btn-primary"><i class="fa fa-clock"></i> Tambah Jam</a><br/><br/>
        <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Jam Masuk Dan Pulang</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap table-sm">
            <thead>
                <tr>
                <th>#</th>
                <th>Hari</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
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
                    <td>{{$item->hari}}</td>
                    <td>{{$item->jam_masuk}}</td>
                    <td>{{$item->jam_pulang}}</td>
                    <td>
                        
                    <form action="/superadmin/jam/{{$item->id}}" method="post">
                        <a href="/superadmin/jam/{{$item->id}}/edit" class="btn btn-xs btn-success"><i class="fas fa-edit"></i> Edit</a>
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