@extends('layouts.app')

@section('title')
EDIT DATA PPPK
@endsection

@section('content')
<br />
<div class="row">
    <div class="col-12">
        <a href="/puskesmas/pppk" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i>
            Kembali</a><br /><br />

        <form method="post" action="/puskesmas/pppk/{{$data->id}}">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-12 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Nama Lengkap</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nama"
                                        placeholder="Masukkan nama lengkap" value="{{$data->nama}}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">NIP</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nip" placeholder="Masukkan NIP (18 digit, tanpa spasi)"
                                        value="{{$data->nip}}" pattern="[0-9]{18}" maxlength="18" title="NIP harus 18 digit angka tanpa spasi" required>
                                    <small class="form-text text-muted">NIP harus 18 digit angka tanpa spasi</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Pangkat</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="pangkat"
                                        placeholder="Masukkan pangkat" value="{{$data->pangkat}}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Tanggal Lahir</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" name="tanggal_lahir"
                                        value="{{\Carbon\Carbon::parse($data->tanggal_lahir)->format('Y-m-d')}}"
                                        required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-block btn-primary"><strong>UPDATE DATA
                                            PPPK</strong></button>
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
