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
            </div>
            <div class="card-body">
                <a href="/laporanharibesar" class="btn btn-md btn-success" target="_blank"><i class="fas fa-file"></i>
                    Laporan
                    Presensi 1 Oct 2025</a>
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