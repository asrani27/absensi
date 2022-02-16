@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
ADMIN
@endsection
@section('content')
<br />
<div class="row">
    <div class="col-12">
        <a href="/admin/pegawai/sync" class="btn btn-sm bg-gradient-purple"><i class="fas fa-sync"></i> Sinkronisasi
            Pegawai</a>
        <a href="/admin/pegawai/createuser" class="btn btn-sm bg-gradient-green"><i class="fas fa-key"></i> Buat Akun
            Login</a>
        {{-- <a href="/admin/pegawai/sortir" class="btn btn-sm bg-gradient-info"><i class="fas fa-users"></i> Urutkan
            Pegawai</a> --}}
        <br /><br />
        @if (Auth::user()->username == '1.02.01.')

        <form method="post" action="/admin/pegawai/puskesmas">
            @csrf
            <select name="puskesmas_id" class='select2 form-control'>
                <option value="">-Pilih-</option>
                @foreach ($puskesmas as $item)
                <option value="{{$item->id}}">{{$item->nama}}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm bg-gradient-green">Tampilkan</button>
        </form>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Pegawai ({{$data->total()}})</h3>
                <div class="card-tools">
                    <form method="get" action="/admin/pegawai/search">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="search" class="form-control float-right" value="{{old('search')}}"
                                placeholder="Nama / NIP">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>NIP/Nama/Jabatan</th>
                            <th>Pangkat</th>
                            <th>Tgl Lahir</th>
                            <th>Dinas/Puskesmas</th>
                            <th>Lokasi Presensi</th>
                            <th>Jenis Presensi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    @php
                    $no =1;
                    @endphp
                    <tbody>
                        @foreach ($data as $key => $item)
                        @if ($item->is_aktif == 0)
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif; background-color:#f2dede">
                            @else
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif; ">
                            @endif
                            <td>{{$no++}}</td>
                            <td>{{$item->nama}}<br />{{$item->nip}}<br />{{$item->jabatan}}</td>
                            <td>{{$item->pangkat}}<br />{{$item->golongan}}</td>
                            <td>{{\Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y')}}</td>
                            <td>{{$item->puskesmas == null ? 'Dinas Kesehatan': $item->puskesmas->nama}}</td>
                            <td>

                                @if ($item->is_aktif == 0)
                                @else
                                @if ($item->lokasi == null)
                                <a href="/admin/pegawai/{{$item->id}}/lokasi" class="btn btn-xs bg-gradient-primary"><i
                                        class="fas fa-pen"></i></a>
                                @else
                                {{$item->lokasi->nama}}
                                <a href="/admin/pegawai/{{$item->id}}/editlokasi"
                                    class="btn btn-xs bg-gradient-success"><i class="fas fa-pen"></i></a>
                                @endif
                                @endif
                            </td>
                            <td>
                                @if ($item->jenis_presensi == 1)
                                5 Hari Kerja
                                @elseif($item->jenis_presensi == 2)
                                6 Hari kerja
                                @else
                                shift
                                @endif

                                <a href="/admin/pegawai/{{$item->id}}/jenispresensi"
                                    class="btn btn-xs bg-gradient-info"><i class="fas fa-edit"></i></a>
                            </td>
                            <td>
                                @if ($item->is_aktif == 0)
                                <strong>STATUS : PENSIUN</strong>

                                @else
                                @if ($item->user == null)
                                <a href="/admin/pegawai/{{$item->id}}/createuser"
                                    class="btn btn-xs bg-gradient-success">Buat Akun</a>
                                @else
                                <a href="/admin/pegawai/{{$item->id}}/presensi"
                                    class="btn btn-xs bg-gradient-warning"><i class="fas fa-calendar"></i> Presensi</a>
                                <a href="/admin/pegawai/{{$item->id}}/resetpass"
                                    class="btn btn-xs bg-gradient-secondary"><i class="fas fa-key"></i> Reset Pass</a>
                                @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        {{$data->links()}}
        @else

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Pegawai ({{$data->total()}})</h3>
                <div class="card-tools">
                    <form method="get" action="/admin/pegawai/search">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="search" class="form-control float-right" value="{{old('search')}}"
                                placeholder="Nama / NIP">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>NIP/Nama/Jabatan</th>
                            <th>Pangkat</th>
                            <th>Tgl Lahir</th>
                            <th>Lokasi Presensi</th>
                            <th>Jenis Presensi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    @php
                    $no =1;
                    @endphp
                    <tbody>
                        @foreach ($data as $key => $item)
                        @if ($item->is_aktif == 0)
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif; background-color:#f2dede">
                            @else
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif; ">
                            @endif
                            <td>{{$no++}}</td>
                            <td>{{$item->nama}}<br />{{$item->nip}}<br />{{$item->jabatan}}</td>
                            <td>{{$item->pangkat}}<br />{{$item->golongan}}</td>
                            <td>{{\Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y')}}</td>
                            <td>

                                @if ($item->is_aktif == 0)
                                @else
                                @if ($item->lokasi == null)
                                <a href="/admin/pegawai/{{$item->id}}/lokasi" class="btn btn-xs bg-gradient-primary"><i
                                        class="fas fa-pen"></i></a>
                                @else
                                {{$item->lokasi->nama}}
                                <a href="/admin/pegawai/{{$item->id}}/editlokasi"
                                    class="btn btn-xs bg-gradient-success"><i class="fas fa-pen"></i></a>
                                @endif
                                @endif
                            </td>
                            <td>
                                @if ($item->jenis_presensi == 1)
                                5 Hari Kerja
                                @elseif($item->jenis_presensi == 2)
                                6 Hari kerja
                                @else
                                shift
                                @endif

                                <a href="/admin/pegawai/{{$item->id}}/jenispresensi"
                                    class="btn btn-xs bg-gradient-info"><i class="fas fa-edit"></i></a>
                            </td>
                            <td>
                                @if ($item->is_aktif == 0)
                                <strong>STATUS : PENSIUN</strong>

                                @else
                                @if ($item->user == null)
                                <a href="/admin/pegawai/{{$item->id}}/createuser"
                                    class="btn btn-xs bg-gradient-success">Buat Akun</a>
                                @else
                                <a href="/admin/pegawai/{{$item->id}}/presensi"
                                    class="btn btn-xs bg-gradient-warning"><i class="fas fa-calendar"></i> Presensi</a>
                                <a href="/admin/pegawai/{{$item->id}}/resetpass"
                                    class="btn btn-xs bg-gradient-secondary"><i class="fas fa-key"></i> Reset Pass</a>
                                @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        {{$data->links()}}
        @endif
    </div>
</div>

@endsection

@push('js')
@endpush