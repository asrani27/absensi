<div class="row">
    <div class="col-12">
        
        <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Lokasi Presensi</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap table-sm">
            <thead>
                <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Lat</th>
                <th>Long</th>
                <th>Radius</th>
                </tr>
            </thead>
            @php
                $no =1;
            @endphp
            <tbody>
            @foreach ($lokasi as $key => $item)
                    <tr>
                    <td>{{$no++}}</td>
                    <td>{{$item->nama}}</td>
                    <td>{{$item->alamat}}</td>
                    <td>{{$item->lat}}</td>
                    <td>{{$item->long}}</td>
                    <td>{{$item->radius}} Meter</td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        </div>
    </div>
</div>