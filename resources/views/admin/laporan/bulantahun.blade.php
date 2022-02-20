@extends('layouts.app')

@push('css')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
    integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
    crossorigin="" />
<style>
    #mapid {
        height: 380px;
    }
</style>
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
                    <span class="username"><a href="#">{{Auth::user()->name}}</a></span>
                    <span class="description">REKAPITULASI PRESENSI</span>
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
                {{$tahun}}
            </div>
            <div class="card-body">
                <a href="/admin/laporan/rekap/{{$bulan}}/{{$tahun}}/pdf" target="_blank"
                    class="btn btn-xs btn-danger">Export PDF</a>
                <a href="/admin/laporan/rekap/{{$bulan}}/{{$tahun}}/masukkanpegawai" class="btn btn-xs btn-primary"
                    onclick="return confirm('Yakin Ingin Memasukkan Semua Pegawai Pada Bulan Ini?');">Masukkan
                    Semua Pegawai & Update Jabatan</a>

                <a href="/admin/laporan/rekap/{{$bulan}}/{{$tahun}}/hitungsemua" class="btn btn-xs btn-primary"
                    onclick="return confirm('Proses ini memakan beberapa waktu, harap di tunggu?');">Hitung Semua</a>

                <br /><br />
                <table class="table table-hover table-striped table-bordered text-nowrap table-sm  table-responsive">
                    <thead>
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif"
                            class="bg-gradient-primary">
                            <th rowspan=2>No</th>
                            <th rowspan=2>NIP</th>
                            <th rowspan=2>Nama</th>
                            <th rowspan=2>Jabatan</th>
                            <th rowspan=2>Jum Hari</th>
                            <th rowspan=2>Jum Jam</th>
                            <th colspan=2 class="text-center">Hadir Di Hari</th>
                            <th colspan=8 class="text-center">Ketidakhadiran*</th>
                            <th colspan=2>Total Absensi</th>
                            <th rowspan=2>Jam <br />Kerja <br />Pegawai</th>
                            <th rowspan=2>Datang <br />Lambat<br />(Jam)</th>
                            <th rowspan=2>Pulang <br />Cepat<br />(Jam)</th>
                            <th rowspan=2>% Hadir</th>
                            <th rowspan=2>Total<br /> Hari<br /> Kerja</th>
                            <th rowspan=2>Aksi</th>
                        </tr>

                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif"
                            class="bg-gradient-primary">
                            <th>Kerja</th>
                            <th>Libur</th>
                            <th>A</th>
                            <th>S</th>
                            <th>TR</th>
                            <th>D</th>
                            <th>I</th>
                            <th>C</th>
                            <th>L</th>
                            <th>O</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                        </tr>
                    </thead>
                    @php
                    $no =1;
                    @endphp
                    <tbody>
                        @foreach ($data as $key => $item)
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif">
                            <td>{{$no++}}</td>
                            <td>{{$item->nip}}</td>
                            <td>{{$item->nama}}</td>
                            <td>{{$item->jabatan}}</td>
                            <td>{{$item->jumlah_hari}}</td>
                            <td>{{intdiv($item->jumlah_jam, 60)}}:{{$item->jumlah_jam % 60}}</td>
                            <td>0</td>
                            <td>0</td>
                            <td>{{$item->a == null ? '0': $item->a}}</td>
                            <td>{{$item->s == null ? '0': $item->s}}</td>
                            <td>{{$item->tr == null ? '0': $item->tr}}</td>
                            <td>{{$item->d == null ? '0': $item->d}}</td>
                            <td>{{$item->i == null ? '0': $item->i}}</td>
                            <td>{{$item->c == null ? '0': $item->c}}</td>
                            <td>{{$item->l == null ? '0': $item->l}}</td>
                            <td>{{$item->o == null ? '0': $item->o}}</td>
                            <td>0</td>
                            <td>0</td>
                            <td>{{intdiv(($item->jumlah_jam - $item->datang_lambat - $item->pulang_cepat),
                                60)}}:{{($item->jumlah_jam
                                - $item->datang_lambat - $item->pulang_cepat) % 60}}</td>
                            <td>{{intdiv($item->datang_lambat, 60)}}:{{$item->datang_lambat % 60}}</td>
                            <td>{{intdiv($item->pulang_cepat, 60)}}:{{$item->pulang_cepat % 60}}</td>
                            <td>{{$item->persen_kehadiran}}</td>
                            <td>0</td>
                            <td>
                                <a href="/admin/ringkasan/{{$item->id}}/delete"
                                    onclick="return confirm('Yakin Ingin Dihapus?');"><span
                                        class="badge badge-danger">Hapus</span></a> |
                                <a href="/admin/ringkasan/{{$item->id}}/hitung/{{$bulan}}/{{$tahun}}"><span
                                        class="badge badge-primary">Hitung</span></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table><br />
                Isi NIP dan Jabatan Lama di bawah ini, Jika Yang bersangkutan sudah pindah/promosi ke skpd lain
                <form method="post" action="/admin/ringkasan/create">
                    @csrf
                    <input type="text" name="nip" class="form-control-sm" placeholder="nip" required>
                    <input type="text" name="jabatan" class="form-control-sm" placeholder="Nama Jabatan" required>
                    <input type="hidden" name="bulan" value="{{$bulan}}" class="form-control-sm" placeholder="bulan">
                    <input type="hidden" name="tahun" value="{{$tahun}}" class="form-control-sm" placeholder="tahun">
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')

@endpush