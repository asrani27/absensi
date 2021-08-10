<div class="row">
    <div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Pegawai</h3>
            <div class="card-tools">
                <form method="get" action="/admin/pegawai/search">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="search" class="form-control float-right" value="{{old('search')}}" placeholder="Nama / NIP">

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
                <th>Tgl Lahir</th>
                <th>Lokasi Presensi</th>
                {{-- <th>Aksi</th> --}}
                </tr>
            </thead>
            @php
                $no =1;
            @endphp
            <tbody>
            @foreach ($pegawai as $key => $item)
                    <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif">
                    <td>{{$pegawai->firstItem() + $key}}</td>
                    <td>{{$item->nama}}<br/>{{$item->nip}}<br/>{{$item->jabatan}}</td>
                    <td>{{\Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y')}}</td>
                    <td>
                        {{$item->lokasi == null ? '': $item->lokasi->nama}}
                        {{-- @if ($item->lokasi == null)
                        <a href="/admin/pegawai/{{$item->id}}/lokasi" class="btn btn-xs bg-gradient-primary"><i class="fas fa-pen"></i></a>
                        @else
                            {{$item->lokasi->nama}}
                            <a href="/admin/pegawai/{{$item->id}}/editlokasi" class="btn btn-xs bg-gradient-success"><i class="fas fa-pen"></i></a>
                        @endif --}}
                    </td>
                    {{-- <td>
                        @if ($item->user == null)
                        <a href="/admin/pegawai/{{$item->id}}/createuser" class="btn btn-xs bg-gradient-success">Buat Akun</a>
                        @else
                            <a href="/admin/pegawai/{{$item->id}}/presensi" class="btn btn-xs bg-gradient-warning"><i class="fas fa-calendar"></i> Presensi</a>
                            <a href="/admin/pegawai/{{$item->id}}/resetpass" class="btn btn-xs bg-gradient-secondary"><i class="fas fa-key"></i> Reset Pass</a>
                        @endif
                    </td> --}}
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    </div>
    {{$pegawai->links()}}
    </div>
</div>