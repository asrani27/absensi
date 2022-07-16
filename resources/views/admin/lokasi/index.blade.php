@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- DataTables -->
<link rel="stylesheet" href="/theme/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/theme/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
@endpush
@section('title')
ADMIN
@endsection
@section('content')
<br />
<div class="row">
    <div class="col-12">
        <a href="/admin/lokasi/create" class="btn btn-sm btn-primary"><i class="fa fa-map-marker"></i> Tambah
            Lokasi</a><br /><br />
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lokasi Presensi</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <table class="table table-hover table-bordered table-sm" id="example1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Lat</th>
                            <th>Long</th>
                            <th>Radius</th>
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
                            <td>{{$item->nama}}</td>
                            <td>{{$item->alamat}}</td>
                            <td>{{$item->lat}}</td>
                            <td>{{$item->long}}</td>
                            <td>{{$item->radius}} Meter</td>
                            <td>

                                <form action="/admin/lokasi/{{$item->id}}" method="post">
                                    <a href="/admin/lokasi/{{$item->id}}/pegawai" class="btn btn-xs btn-info"><i
                                            class="fas fa-users"></i> Pegawai</a>
                                    <a href="/admin/lokasi/{{$item->id}}/edit" class="btn btn-xs btn-success"><i
                                            class="fas fa-edit"></i> Edit</a>
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-xs btn-danger"
                                        onclick="return confirm('yakin DI Hapus?');"><i class="fas fa-trash"></i>
                                        Delete</button>
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
<!-- DataTables -->
<script src="/theme/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/theme/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/theme/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/theme/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script>
    $(function () {
      $("#example1").DataTable({
        "responsive": true,
        "autoWidth": false,
      });
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      });
    });
</script>
@endpush