<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        Laporan presensi Per Tanggal
      </div>
      <div class="card-body">
        <form method="get" action="/superadmin/laporan/tanggal" target="_blank">
          @csrf
          <div class="row">
            <div class="col-sm-10">
              <div class="form-group">
                <input type="date" name="tanggal" class="form-control"
                  value="{{\Carbon\Carbon::today()->format('Y-m-d')}}">
                <input type="hidden" name="skpd_id" value="{{$skpd->id}}">
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <button type="submit" class="btn btn-danger">Print</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        Laporan presensi Per Bulan
      </div>
      <div class="card-body">

        <form method="get" action="/admin/laporan/rekap" target="_blank">
          @csrf
          <div class="row">
            <div class="col-sm-5">
              <div class="form-group">
                <select class="form-control" name="bulan" required>
                  <option value="">-pilih bulan-</option>
                  <option value="01" {{$bulan=='01' ? 'selected' :''}}>Januari</option>
                  <option value="02" {{$bulan=='02' ? 'selected' :''}}>Februari</option>
                  <option value="03" {{$bulan=='03' ? 'selected' :''}}>Maret</option>
                  <option value="04" {{$bulan=='04' ? 'selected' :''}}>April</option>
                  <option value="05" {{$bulan=='05' ? 'selected' :''}}>Mei</option>
                  <option value="06" {{$bulan=='06' ? 'selected' :''}}>Juni</option>
                  <option value="07" {{$bulan=='07' ? 'selected' :''}}>Juli</option>
                  <option value="08" {{$bulan=='08' ? 'selected' :''}}>Agustus</option>
                  <option value="09" {{$bulan=='09' ? 'selected' :''}}>September</option>
                  <option value="10" {{$bulan=='10' ? 'selected' :''}}>Oktober</option>
                  <option value="11" {{$bulan=='11' ? 'selected' :''}}>November</option>
                  <option value="12" {{$bulan=='12' ? 'selected' :''}}>Desember</option>
                </select>
              </div>
            </div>

            <div class="col-sm-5">
              <div class="form-group">
                <select class="form-control" name="tahun" required>
                  <option value="">-pilih tahun-</option>
                  <option value="2021" {{$tahun=='2021' ? 'selected' :''}}>2021</option>
                  <option value="2022" {{$tahun=='2022' ? 'selected' :''}}>2022</option>
                  <option value="2023" {{$tahun=='2023' ? 'selected' :''}}>2023</option>
                </select>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <button type="submit" name="button" value="1" class="btn btn-danger">Print</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>