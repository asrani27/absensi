@extends('layouts.app')

@push('css')
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
    SETTING JAM
@endsection
@section('content')
<br/>
<div class="row">
    <div class="col-12">
        <a href="/superadmin/generatetanggal/01" class="btn btn-sm btn-primary"><i class="fa fa-clock"></i> Januari</a>
        <a href="/superadmin/generatetanggal/02" class="btn btn-sm btn-primary"><i class="fa fa-clock"></i> Februari</a>
        <a href="/superadmin/generatetanggal/03" class="btn btn-sm btn-primary"><i class="fa fa-clock"></i> Maret</a>
        <a href="/superadmin/generatetanggal/04" class="btn btn-sm btn-primary"><i class="fa fa-clock"></i> April</a>
        <a href="/superadmin/generatetanggal/05" class="btn btn-sm btn-primary"><i class="fa fa-clock"></i> Mei</a>
        <a href="/superadmin/generatetanggal/06" class="btn btn-sm btn-primary"><i class="fa fa-clock"></i> Juni</a>
        <a href="/superadmin/generatetanggal/07" class="btn btn-sm btn-primary"><i class="fa fa-clock"></i> Juli</a>
        <a href="/superadmin/generatetanggal/08" class="btn btn-sm btn-primary"><i class="fa fa-clock"></i> Agustus</a>
        <a href="/superadmin/generatetanggal/09" class="btn btn-sm btn-primary"><i class="fa fa-clock"></i> September</a>
        <a href="/superadmin/generatetanggal/10" class="btn btn-sm btn-primary"><i class="fa fa-clock"></i> Oktober</a>
        <a href="/superadmin/generatetanggal/11" class="btn btn-sm btn-primary"><i class="fa fa-clock"></i> November</a>
        <a href="/superadmin/generatetanggal/12" class="btn btn-sm btn-primary"><i class="fa fa-clock"></i> Desember</a>
        
        <br/><br/>
        
    </div>
</div>

@endsection

@push('js')
@endpush