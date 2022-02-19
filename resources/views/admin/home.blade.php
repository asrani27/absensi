@extends('layouts.app')

@push('css')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
    integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
    crossorigin="" />
<style>
    #mapid {
        height: 380px;
    }
</style>
@endpush
@section('title')
<strong>PRESENSI</strong>
@endsection
@section('content')
<br />
<div class="row">
    <div class="col-lg-12">
        <div class="card card-widget">
            <div class="card-header">
                <div class="user-block">
                    <img class="img-circle"
                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/User_icon_2.svg/2048px-User_icon_2.svg.png"
                        alt="User Image">
                    <span class="username"><a href="#">{{Auth::user()->name}}</a></span>
                    <span class="description">SELAMAT DATANG DI APLIKASI ADMIN PRESENSI, HARAP SETTING LAT DAN
                        LONG</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                Panduan Edit Presensi Pegawai
            </div>
            <div class="card-body">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/GjggZkQOXFQ"
                    title="YouTube video player" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
                {{-- Pilih Menu Pegawai<br />
                <img src="/theme/panduan.png" width="20%" height="90"><br />
                KLik Tombol Presensi<br />
                <img src="/theme/panduan2.png" width="80%" height="60"> --}}
                {{-- <form method="get" action="/admin/tampilgenerate">
                    @csrf
                    <div class="row">
                        <div class="col-sm-10">
                            <div class="form-group">
                                <input type="date" name="tanggal" class="form-control" id="inputSuccess"
                                    value="{{old('tanggal') == null ? \Carbon\Carbon::today()->format('Y-m-d'):old('tanggal')}}">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" name="button" value="1">Tampilkan</button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class=" table-responsive">
                    <table class="table table-hover table-striped table-bordered text-nowrap table-sm">
                        <thead>
                            <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif"
                                class="bg-gradient-primary">
                                <th class="text-center">#</th>
                                <th>Nama/NIP</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Jam Masuk</th>
                                <th class="text-center">Jam Pulang</th>
                                <th>Keterangan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        @php
                        $no =1;
                        @endphp
                        <tbody>
                            @foreach ($data as $key => $item)
                            <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif">
                                <td class="text-center">{{$no++}}</td>
                                <td>{{$item->nama}}<br />{{$item->nip}}</td>
                                <td class="text-center">{{\Carbon\Carbon::parse($item->tanggal)->isoFormat('D MMMM
                                    Y')}}
                                </td>
                                <td class="text-center">{{$item->jam_masuk == null ? '00:00:00': $item->jam_masuk}}
                                </td>
                                <td class="text-center">{{$item->jam_pulang == null ? '00:00:00':
                                    $item->jam_pulang}}
                                </td>
                                <td>{{$item->keterangan}}</td>
                                <td class="text-center">
                                    @if ($item->hapus == 1)

                                    <a href="/admin/presensi/{{$item->id}}" class="btn btn-xs btn-info">Presensi</a>
                                    @else
                                    <a href="/admin/presensi/{{$item->id}}" class="btn btn-xs btn-info">Presensi</a>
                                    <a href="/admin/presensi/{{$item->id}}/delete" class="btn btn-xs btn-danger">X</a>

                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div> --}}
            </div>
        </div>
    </div>
</div>

{{-- <div class="modal fade" id="isipresensi">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Edit Presensi </h4>
            </div>
            <form class="form-horizontal" method="post">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" class="form-control" id="idpresensi" name="idpresensi">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Jam Masuk</label>
                        <div class="col-sm-8">
                            <input type="time" class="form-control" name="jam_masuk">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Jam Pulang</label>
                        <div class="col-sm-8">
                            <input type="time" class="form-control" name="jam_pulang">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div> --}}
@endsection

@push('js')
{{-- <script>
    $(document).on('click', '.isi-presensi', function() {
    var id_presensi = $(this).data('id');
    $('#idpresensi').val($(this).data('id'));
    $('#isipresensi').modal('show');
});
</script> --}}
@endpush