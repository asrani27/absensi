<table>
    <thead>
    <tr>
        <th>NO</th>
        <th>NIP</th>
        <th>NAMA</th>
        <th>SKPD</th>
        <th>JANUARI</th>
        <th>FEBRUARI</th>
        <th>MARET</th>
        <th>APRIL</th>
        <th>MEI</th>
        <th>JUNI</th>
        <th>JULI</th>
        <th>AGUSTUS</th>
        <th>SEPTEMBER</th>
        <th>OKTOBER</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $key => $item)
        <tr>
            <td>{{$key + 1}}</td>
            <td>'{{ $item->nip }}</td>
            <td>{{ $item->nama }}</td>
            <td>{{ $item->skpd == null ? '': $item->skpd->nama }}</td>
            
            <td>{{ $item->bulan_01 }}</td>
            <td>{{ $item->bulan_02 }}</td>
            <td>{{ $item->bulan_03 }}</td>
            <td>{{ $item->bulan_04 }}</td>
            <td>{{ $item->bulan_05 }}</td>
            <td>{{ $item->bulan_06 }}</td>
            <td>{{ $item->bulan_07 }}</td>
            <td>{{ $item->bulan_08 }}</td>
            <td>{{ $item->bulan_09 }}</td>
            <td>{{ $item->bulan_10 }}</td>
        </tr>
    @endforeach
    </tbody>
</table>