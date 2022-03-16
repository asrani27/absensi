@extends('layouts.app')

@push('css')

@endpush
@section('title')
<strong>PRESENSI</strong>
@endsection
@section('content')
<br />
<div class="row">
    <div class="col-lg-12">
        <a href="/admin/puskesmas" class="btn btn-sm bg-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        <br />
        <br />
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                Ganti Pass
            </div>
            <form method="post" action="/admin/puskesmas/{{$id}}/gantipass">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Rs / Puskesmas</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{$data->nama}}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Masukkan Password Baru</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="password1" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Masukkan lagi</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="password2" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-block btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('js')

@endpush