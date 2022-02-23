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
        <div class="card card-widget">
            <div class="card-header">
                <div class="user-block">
                    <img class="img-circle" src="/theme/dist/img/user1-128x128.jpg" alt="User Image">
                    <span class="username"><a href="#">{{Auth::user()->name}}</a></span>
                    <span class="description">SELAMAT DATANG DI APLIKASI PRESENSI</span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                Laporan presensi Bulan {{\Carbon\Carbon::createFromFormat('m', $bulan)->translatedFormat('F')}}
            </div>
            <div class="card-body">
                <i class="fas fa-lock"></i>: Terkunci, skpd tidak bisa mengubah data<br />
                <i class="fas fa-unlock"></i>: Terbuka, skpd dapat mengubah data
                <table class="table table-hover table-striped text-nowrap table-sm">
                    <thead>
                        <tr style="font-size:12px; font-family:Arial, Helvetica, sans-serif"
                            class="bg-gradient-primary">
                            <th>#</th>
                            <th>SKPD & Puskesmas</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    @php
                    $no =1;
                    @endphp
                    <tbody>

                        @foreach ($skpd as $key => $item)
                        <tr style="font-size:12px; font-family:Arial, Helvetica, sans-serif">
                            <td>{{$no++}}</td>
                            <td>{{$item->nama}}</td>
                            <td style="font-size: 16px">
                                @if (kunciSkpd($item->id,$bulan,$tahun) == null)
                                <a href="/superadmin/rekapitulasi/{{$bulan}}/{{$tahun}}/skpd/{{$item->id}}/lock"
                                    style="color:black" onclick="return confirm('Yakin ingin dikunci?');"><i
                                        class="fas fa-unlock"></i></a>
                                @else
                                <a href="/superadmin/rekapitulasi/{{$bulan}}/{{$tahun}}/skpd/{{$item->id}}/unlock"><i
                                        class="fas fa-lock" onclick="return confirm('Yakin ingin dibuka?');"></i></a>
                                @endif
                            </td>
                            <td>
                                <a href="/superadmin/rekapitulasi/{{$bulan}}/{{$tahun}}/skpd/{{$item->id}}/pdf"
                                    target="_blank" class="btn btn-xs btn-danger"><i class="fas fa-file"></i> Print</a>
                            </td>
                        </tr>
                        @endforeach
                        @foreach ($puskesmas as $key => $item)
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif">
                            <td>{{$no++}}</td>
                            <td>{{$item->nama}}</td>
                            <td style="font-size: 16px">
                                @if (kunciPuskesmas($item->id,$bulan,$tahun) == null)
                                <a href="/superadmin/rekapitulasi/{{$bulan}}/{{$tahun}}/puskesmas/{{$item->id}}/lock"
                                    style="color:black" onclick="return confirm('Yakin ingin dikunci?');"><i
                                        class="fas fa-unlock"></i></a>
                                @else
                                <a href="/superadmin/rekapitulasi/{{$bulan}}/{{$tahun}}/puskesmas/{{$item->id}}/unlock"><i
                                        class="fas fa-lock" onclick="return confirm('Yakin ingin dibuka?');"></i></a>
                                @endif
                            </td>
                            <td><a href="/superadmin/rekapitulasi/{{$bulan}}/{{$tahun}}/puskesmas/{{$item->id}}/pdf"
                                    target="_blank" class="btn btn-xs btn-danger"><i class="fas fa-file"></i> Print</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')

@endpush