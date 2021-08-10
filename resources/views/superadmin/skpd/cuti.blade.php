<div class="row">
    <div class="col-12">        
        <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Cuti</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap table-sm">
            <thead>
                <tr>
                <th>#</th>
                <th>NIP</th>
                <th>Nama</th>
                <th>Tgl Mulai</th>
                <th>Tgl Selesai</th>
                <th>Jenis</th>
                <th>Dokumen</th>
                <th>Keterangan</th>
                </tr>
            </thead>
            @php
                $no =1;
            @endphp
            <tbody>
            @foreach ($cuti as $key => $item)
                    <tr>
                    <td>{{$no++}}</td>
                    <td>{{$item->nip}}</td>
                    <td>{{$item->nama}}</td>
                    <td>{{\Carbon\Carbon::parse($item->tanggal_mulai)->isoFormat('D MMMM Y')}}</td>
                    <td>{{\Carbon\Carbon::parse($item->tanggal_selesai)->isoFormat('D MMMM Y')}}</td>
                    <td>{{$item->jenis_keterangan->keterangan}}</td>
                    <td>{{$item->dokumen}}</td>
                    <td>{{$item->keterangan}}</td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        </div>
    </div>
</div>
