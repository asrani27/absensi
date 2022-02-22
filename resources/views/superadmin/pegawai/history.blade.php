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
                    <img class="img-circle"
                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/User_icon_2.svg/2048px-User_icon_2.svg.png"
                        alt="User Image">
                    <span class="username"><a href="#">{{$pegawai->nama}}</a></span>
                    <span class="description">{{$pegawai->nip}} <br />{{$pegawai->skpd->nama}}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                Data Presensi
            </div>
            <div class="card-body">
                <form method="get" action="/superadmin/pegawai/{{$pegawai->id}}/history/tampilkan">
                    @csrf
                    <div class="row">

                        <div class="col-sm-5">
                            <div class="form-group">
                                <select class="form-control" name="bulan" required>
                                    <option value="">-pilih bulan-</option>
                                    <option value="01" {{old('bulan')=='01' ? 'selected' :''}}>Januari</option>
                                    <option value="02" {{old('bulan')=='02' ? 'selected' :''}}>Februari</option>
                                    <option value="03" {{old('bulan')=='03' ? 'selected' :''}}>Maret</option>
                                    <option value="04" {{old('bulan')=='04' ? 'selected' :''}}>April</option>
                                    <option value="05" {{old('bulan')=='05' ? 'selected' :''}}>Mei</option>
                                    <option value="06" {{old('bulan')=='06' ? 'selected' :''}}>Juni</option>
                                    <option value="07" {{old('bulan')=='07' ? 'selected' :''}}>Juli</option>
                                    <option value="08" {{old('bulan')=='08' ? 'selected' :''}}>Agustus</option>
                                    <option value="09" {{old('bulan')=='09' ? 'selected' :''}}>September</option>
                                    <option value="10" {{old('bulan')=='10' ? 'selected' :''}}>Oktober</option>
                                    <option value="11" {{old('bulan')=='11' ? 'selected' :''}}>November</option>
                                    <option value="12" {{old('bulan')=='12' ? 'selected' :''}}>Desember</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-5">
                            <div class="form-group">
                                <select class="form-control" name="tahun" required>
                                    <option value="">-pilih tahun-</option>
                                    <option value="2022" {{old('tahun')=='2022' ? 'selected' :''}}>2022</option>
                                    <option value="2023" {{old('tahun')=='2023' ? 'selected' :''}}>2023</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <button type="submit" value="1" class="btn btn-sm btn-primary">Tampilkan</button>
                                <button type="submit" value="2" class="btn btn-sm btn-danger">Perbaiki</button>
                                {{-- <a href="/admin/generate/presensi" class="btn btn-warning">Generate</a> --}}
                            </div>
                        </div>
                    </div>
                </form>

                @if ($data != null)
                <table class="table table-hover table-striped text-nowrap table-sm">
                    <thead>
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif"
                            class="bg-gradient-primary">
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Hari</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Keterangan</th>
                            <th>Telat</th>
                            <th>Lebih awal</th>
                            <th></th>
                        </tr>
                    </thead>
                    @php
                    $no =1;
                    @endphp
                    <tbody>
                        @foreach ($data as $key => $item)
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif">
                            <td>{{$no++}}</td>
                            <td>{{\Carbon\Carbon::parse($item->tanggal)->format('d M Y')}}</td>
                            <td>{{\Carbon\Carbon::parse($item->tanggal)->translatedFormat('l')}}</td>
                            <td>{{$item->jam_masuk == null ? '00:00:00': $item->jam_masuk}}</td>
                            <td>{{$item->jam_pulang == null ? '00:00:00': $item->jam_pulang}}</td>
                            <td>
                                {{$item->jenis_keterangan == null ? '' :$item->jenis_keterangan->keterangan}}
                            </td>
                            <td>{{$item->terlambat}}</td>
                            <td>{{$item->lebih_awal}}</td>
                            <td><a href="/superadmin/presensipegawai/{{$item->id}}"
                                    onclick="return confirm('Yakin ingin dihapus?');">delete</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection

@push('js')

@endpush