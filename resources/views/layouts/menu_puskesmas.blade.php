<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column text-sm" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            <a href="/home/puskesmas" class="nav-link {{Request::is('home/puskesmas') ? 'active' : ''}}">
                <i class="nav-icon fas fa-home"></i>
                <p>
                    Beranda
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="/puskesmas/pegawai" class="nav-link {{Request::is('puskesmas/pegawai') ? 'active' : ''}}">
                <i class="nav-icon fas fa-users"></i>
                <p>
                    Pegawai
                </p>
            </a>
        </li>

        <li class="nav-item">
            <a href="/puskesmas/cuti" class="nav-link {{Request::is('puskesmas/cuti') ? 'active' : ''}}">
                <i class="nav-icon fas fa-calendar"></i>
                <p>
                    Cuti/TL/Izin/Sakit
                </p>
            </a>
        </li>

        <li class="nav-item">
            <a href="/puskesmas/gantipass" class="nav-link {{Request::is('puskesmas/gantipass') ? 'active' : ''}}">
                <i class="nav-icon fas fa-key"></i>
                <p>
                    Ganti Password
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="/logout" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>
                    Logout
                </p>
            </a>
        </li>
    </ul>
</nav>