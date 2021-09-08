@extends('layouts.error')
@push('css')
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
    PRESENSI
@endsection
@section('content')
<br/>
<div class="row">
    <div class="col-12 text-center">
        
        <a href="{{route('login')}}" class="btn btn-lg btn-primary refresh">REFRESH KLIK DISINI</a>
        <div class="col-12" id="btnLoading">
        <button type="button" class="btn btn-block bg-gradient-primary btnLoading"><i class="fas fa-sync-alt fa-spin"></i> Harap menunggu...</button>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
$(document).ready(function () {
    
    var loading = document.getElementById("btnLoading");
    loading.style.display = "none";
    $(".refresh").click(function(){
        $(".refresh").hide();
        loading.style.display = "block";
    });      
   });
    </script>
@endpush