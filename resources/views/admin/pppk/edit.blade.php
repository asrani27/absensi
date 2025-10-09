@extends('layouts.app')

@section('title')
EDIT DATA PPPK
@endsection

@section('content')
<br />
<div class="row">
    <div class="col-12">
        <a href="/admin/pppk" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i>
            Kembali</a><br /><br />

        <form method="post" action="/admin/pppk/{{$data->id}}">
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
                                    <input type="text" class="form-control" name="nip"
                                        placeholder="Masukkan NIP (18 digit, tanpa spasi)" value="{{$data->nip}}"
                                        pattern="[0-9]{18}" maxlength="18" title="NIP harus 18 digit angka tanpa spasi"
                                        required>
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
                                <label class="col-sm-2 col-form-label">Golongan</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="golongan"
                                        placeholder="Masukkan golongan" value="{{$data->golongan}}" required>
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
                                <label class="col-sm-2 col-form-label">Status ASN</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="status_asn">
                                        <option value="">-- Pilih Status --</option>
                                        <option value="PNS" {{old('status_asn', $data->status_asn) == 'PNS' ? 'selected'
                                            : ''}}>PNS</option>
                                        <option value="PPPK" {{old('status_asn', $data->status_asn) == 'PPPK' ?
                                            'selected' : ''}}>PPPK</option>
                                        <option value="HONORER" {{old('status_asn', $data->status_asn) == 'HONORER' ?
                                            'selected' : ''}}>HONORER</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Puskesmas</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="puskesmas_id">
                                        <option value="">-- Pilih Puskesmas --</option>
                                        @foreach ($puskesmas as $item)
                                        <option value="{{$item->id}}" {{old('puskesmas_id', $data->puskesmas_id) ==
                                            $item->id ? 'selected' : ''}}>{{$item->nama}}</option>
                                        @endforeach
                                    </select>
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