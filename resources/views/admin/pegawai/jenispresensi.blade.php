@extends('layouts.app')

@push('css')

<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

@endpush
@section('title')
JENIS PRESENSI
@endsection
@section('content')
<br />
<div class="row">
    <div class="col-12">
        <a href="/admin/pegawai" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i>
            Kembali</a><br /><br />
        <form method="post" action="/admin/pegawai/{{$data->id}}/jenispresensi">
            @csrf
            <div class="row">
                <div class="col-lg-12 col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">NIP</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nip" required readonly
                                        value="{{$data->nip}}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Nama</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nama" required readonly
                                        value="{{$data->nama}}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Jenis Presensi</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="jenis_presensi" required>
                                        <option value='1' {{$data->jenis_presensi == 1 ? 'selected':''}}>5 Hari Kerja
                                        </option>
                                        <option value='2' {{$data->jenis_presensi == 2 ? 'selected':''}}>6 Hari Kerja
                                        </option>
                                        <option value='3' {{$data->jenis_presensi == 3 ? 'selected':''}}>Shift</option>

                                        <option value='4' {{$data->jenis_presensi == 4 ? 'selected':''}}>5 Hari Kerja (Sekolah)
                                        </option>
                                        <option value='5' {{$data->jenis_presensi == 5 ? 'selected':''}}>6 Hari Kerja (Sekolah)
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-block btn-primary"><strong>UPDATE JENIS
                                            PRESENSI</strong></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('js')

@endpush