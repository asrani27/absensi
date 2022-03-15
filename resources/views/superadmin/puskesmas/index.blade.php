@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
SKPD
@endsection
@section('content')
<br />
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data RS dan Puskesmas</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode</th>
                            <th>Nama RS / Puskesmas</th>
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
                            <td>{{$item->kode_skpd}}</td>
                            <td>{{$item->nama}}</td>
                            <td>
                                @if ($item->user_id == null)
                                <a href="/superadmin/skpd/{{$item->id}}/buatakun" class="btn btn-xs btn-warning"><i
                                        class="fas fa-key"></i> Buat Akun</a>
                                @else
                                <a href="/superadmin/skpd/{{$item->id}}/resetpass" class="btn btn-xs btn-success"><i
                                        class="fas fa-key"></i> Reset Pass</a>
                                @endif
                                <a href="/superadmin/skpd/{{$item->id}}/detail" class="btn btn-xs btn-primary"><i
                                        class="fas fa-eye"></i> Detail</a>
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