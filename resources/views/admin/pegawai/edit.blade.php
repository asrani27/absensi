@extends('layouts.app')

@section('title')
<strong>Edit Pegawai</strong>
@endsection

@section('content')
<br />
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Data Pegawai</h3>
                <div class="card-tools">
                    <a href="/admin/pegawai" class="btn btn-sm btn-default">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form method="POST" action="/admin/pegawai/{{$pegawai->id}}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama" value="{{old('nama', $pegawai->nama)}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nip">NIP</label>
                                <input type="text" class="form-control" id="nip" name="nip" value="{{old('nip', $pegawai->nip)}}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jabatan">Jabatan</label>
                                <input type="text" class="form-control" id="jabatan" name="jabatan" value="{{old('jabatan', $pegawai->jabatan)}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pangkat">Pangkat</label>
                                <input type="text" class="form-control" id="pangkat" name="pangkat" value="{{old('pangkat', $pegawai->pangkat)}}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="golongan">Golongan</label>
                                <input type="text" class="form-control" id="golongan" name="golongan" value="{{old('golongan', $pegawai->golongan)}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_lahir">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{old('tanggal_lahir', \Carbon\Carbon::parse($pegawai->tanggal_lahir)->format('Y-m-d'))}}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status_asn">Status ASN</label>
                                <select class="form-control" id="status_asn" name="status_asn">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="PNS" {{old('status_asn', $pegawai->status_asn) == 'PNS' ? 'selected' : ''}}>PNS</option>
                                    <option value="PPPK" {{old('status_asn', $pegawai->status_asn) == 'PPPK' ? 'selected' : ''}}>PPPK</option>
                                    <option value="HONORER" {{old('status_asn', $pegawai->status_asn) == 'HONORER' ? 'selected' : ''}}>HONORER</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="puskesmas_id">Puskesmas</label>
                                <select class="form-control" id="puskesmas_id" name="puskesmas_id">
                                    <option value="">-- Pilih Puskesmas --</option>
                                    <option value="34" {{old('puskesmas_id', $pegawai->puskesmas_id) == '34' ? 'selected' : ''}}>Dinas Kesehatan</option>
                                    @foreach ($puskesmas as $item)
                                    <option value="{{$item->id}}" {{old('puskesmas_id', $pegawai->puskesmas_id) == $item->id ? 'selected' : ''}}>{{$item->nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                                <a href="/admin/pegawai" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    // Tambahkan validasi jika diperlukan
    $('form').on('submit', function(e) {
        // Validasi dasar bisa ditambahkan di sini
        return true;
    });
});
</script>
@endpush
