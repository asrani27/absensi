@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush

@section('title')
UPLOAD DATA
@endsection
@section('content')
<br />
<div class="row">
    <div class="col-12">
        <a href="/admin/cuti" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a><br /><br />

        <form method="post" action="/admin/cuti/upload/{{$id}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-12 col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Dokumen</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" name="file">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <button type="submit"
                                        class="btn btn-block btn-primary"><strong>UPLOAD</strong></button>
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