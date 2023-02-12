@extends('layouts.app')

@push('css')

<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush
@section('title')
VERIFIKATOR
@endsection
@section('content')
<br />
<div class="row">
    <div class="col-12">
        <a href="/admin/pegawai" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i>
            Kembali</a><br /><br />
        <form method="post" action="/admin/pegawai/verifikator">
            @csrf
            <div class="row">
                <div class="col-lg-12 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Verifikator</label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" name="verifikator" required>
                                        <option value="">-pilih-</option>
                                        @foreach ($pegawai as $item)
                                            <option value="{{$item->nip}}" {{$data->kadis == $item->nip ? 'selected':''}}>{{$item->nip}} - {{$item->nama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-block btn-primary"><strong>Update Verifikator</strong></button>
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
<script src="/theme/plugins/select2/js/select2.full.min.js"></script>
<script>
    $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
})
</script>
@endpush