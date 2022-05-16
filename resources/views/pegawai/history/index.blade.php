@extends('layouts.app')

@push('css')

@endpush
@section('title')
<strong>PRESENSI</strong>
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form method="get" action="/pegawai/presensi/history/search">
                    @csrf
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Bulan</label>
                        <div class="col-sm-10">
                            <select name="bulan" class="form-control">
                                <option value="">-Bulan-</option>
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
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tahun</label>
                        <div class="col-sm-10">
                            <select name="tahun" class="form-control">
                                <option value="">-Tahun-</option>
                                <option value="2022" {{old('tahun')=='2022' ? 'selected' :''}}>2022</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <button type="submit" class="btn btn-block bg-gradient-success">TAMPILKAN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">History Presensi Bulan : {{old('bulan')}}/{{old('tahun')}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap table-sm">
                    <thead>
                        <tr style="font-size:12px; font-family:Arial, Helvetica, sans-serif">
                            <th>Tgl</th>
                            <th>Hari</th>
                            <th class="text-center">Jam Masuk</th>
                            <th class="text-center">Jam Pulang</th>
                            {{-- <th class="text-center">Telat</th>
                            <th class="text-center">Lebih Awal</th> --}}
                        </tr>
                    </thead>
                    @php
                    $no =1;
                    @endphp
                    @if (Auth::user()->pegawai->jenis_presensi == 3)
                    <tbody>
                        @foreach ($data as $key => $item)
                        <tr style="font-size:10px; font-family:Arial, Helvetica, sans-serif">
                            <td>{{\Carbon\Carbon::parse($item->tanggal)->format('d')}}</td>
                            <td>{{\Carbon\Carbon::parse($item->tanggal)->translatedFormat('l')}}</td>
                            <td class="text-center">{{$item->shift_jam_masuk == null ? '00:00:00':
                                \Carbon\Carbon::parse($item->shift_jam_masuk)->format('d-m-Y H:i:s')}}</td>
                            <td class="text-center">{{$item->shift_jam_pulang == null ? '00:00:00':
                                \Carbon\Carbon::parse($item->shift_jam_pulang)->format('d-m-Y H:i:s')}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    @else

                    <tbody>
                        @foreach ($data as $key => $item)
                        @if (\Carbon\Carbon::parse($item->tanggal)->translatedFormat('l') == 'Minggu')
                        <tr style="background-color: #f2dede;font-size:10px; font-family:Arial, Helvetica, sans-serif">
                            @else
                        <tr style="background-color: #dff0d8;font-size:10px; font-family:Arial, Helvetica, sans-serif">
                            @endif
                            <td>{{\Carbon\Carbon::parse($item->tanggal)->format('d')}}</td>
                            <td>{{\Carbon\Carbon::parse($item->tanggal)->translatedFormat('l')}}</td>
                            <td class="text-center">{{$item->jam_masuk == null ?
                                '':\Carbon\Carbon::parse($item->jam_masuk)->format('H:i:s')}}<br />
                                @if ($item->photo_masuk == null)

                                @else
                                <img src="{{$item->photo_masuk}}" width="75px" height="25px">
                                @endif
                            </td>
                            <td class="text-center">{{$item->jam_pulang == null ?
                                '':\Carbon\Carbon::parse($item->jam_pulang)->format('H:i:s')}}<br />
                                @if ($item->photo_pulang == null)

                                @else
                                <img src="{{$item->photo_pulang}}" width="75px" height="25px">
                                @endif
                            </td>
                            {{-- <td class="text-center">{{$item->terlambat}}</td>
                            <td class="text-center">{{$item->lebih_awal}}</td> --}}
                        </tr>
                        @endforeach
                        {{-- <tr
                            style="background-color: #dff0d8;font-size:10px; font-family:Arial, Helvetica, sans-serif">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center">{{$data->sum('terlambat')}} Menit</td>
                            <td class="text-center">{{$data->sum('lebih_awal')}} Menit</td>
                        </tr>
                        <tr style="background-color: #dff0d8;font-size:10px; font-family:Arial, Helvetica, sans-serif">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Persentase Kehadiran</td>
                            <td>{{persenKehadiran(Auth::user()->username, $bulan, $tahun)->persen_kehadiran}}</td>
                        </tr> --}}
                    </tbody>
                    @endif
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>
@endsection

@push('js')

@endpush