<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column text-sm" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            <a href="/home/admin" class="nav-link {{Request::is('home/admin') ? 'active' : ''}}">
                <i class="nav-icon fas fa-home"></i>
                <p>
                    Beranda
                </p>
            </a>
        </li>
        @if (Auth::user()->username == '1.02.01.')
        <li class="nav-item">
            <a href="/admin/puskesmas" class="nav-link {{Request::is('admin/puskesmas') ? 'active' : ''}}">
                <i class="nav-icon fas fa-home"></i>
                <p>
                    RS & Puskesmas
                </p>
            </a>
        </li>
        @endif
        <li class="nav-item">
            <a href="/admin/lokasi" class="nav-link {{Request::is('admin/lokasi') ? 'active' : ''}}">
                <i class="nav-icon fa fa-map-marker"></i>
                <p>
                    Lokasi Absen
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="/admin/pegawai" class="nav-link {{Request::is('admin/pegawai') ? 'active' : ''}}">
                <i class="nav-icon fas fa-users"></i>
                <p>
                    Pegawai
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="/admin/perubahandata" class="nav-link {{Request::is('admin/perubahandata') ? 'active' : ''}}">
                <i class="nav-icon fas fa-history"></i>
                <p>
                    History Perubahan Data
                </p>
            </a>
        </li>

        {{-- <li class="nav-item">
            <a href="/admin/qrcode" class="nav-link {{Request::is('admin/qrcode') ? 'active' : ''}}">
                <i class="nav-icon fas fa-qrcode"></i>
                <p>
                    QR Code
                </p>
            </a>
        </li> --}}
        <li class="nav-item">
            <a href="/admin/cuti" class="nav-link {{Request::is('admin/cuti') ? 'active' : ''}}">
                <i class="nav-icon fas fa-calendar"></i>
                <p>
                    Cuti/TL/Izin/Sakit
                </p>
            </a>
        </li>

        <li class="nav-item">
            <a href="/admin/laporan" class="nav-link {{Request::is('admin/laporan') ? 'active' : ''}}">
                <i class="nav-icon fas fa-file"></i>
                <p>
                    Laporan
                </p>
            </a>
        </li>

        <li class="nav-item">
            <a href="/admin/gantipass" class="nav-link {{Request::is('admin/gantipass') ? 'active' : ''}}">
                <i class="nav-icon fas fa-key"></i>
                <p>
                    Ganti Password
                </p>
            </a>
        </li>

        @if (session()->get('uuid') != null)
        <li class="nav-item">
            <a href="/admin/superadmin/{{session()->get('uuid')}}" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>
                    Ke Superadmin
                </p>
            </a>
        </li>
        @else
        <li class="nav-item">
            <a href="/logout" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>
                    Logout
                </p>
            </a>
        </li>
        @endif
    </ul>
</nav>