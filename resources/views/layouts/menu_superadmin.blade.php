<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            <a href="/home/superadmin" class="nav-link {{ Request::is('home/superadmin*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-home"></i>
                <p>
                    Beranda
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="/superadmin/skpd" class="nav-link {{ Request::is('superadmin/skpd*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-home"></i>
                <p>
                    SKPD
                </p>
            </a>
        </li>

        <li class="nav-item">
            <a href="/superadmin/pegawai" class="nav-link {{ Request::is('superadmin/pegawai*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users"></i>
                <p>
                    PEGAWAI
                </p>
            </a>
        </li>

        <li class="nav-item">
            <a href="/superadmin/cuti" class="nav-link {{ Request::is('superadmin/cuti*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users"></i>
                <p>
                    CUTI
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="/superadmin/rekapitulasi"
                class="nav-link {{ Request::is('superadmin/rekapitulasi*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users"></i>
                <p>
                    REKAPITULASI
                </p>
            </a>
        </li>
        <li class="nav-header">SETTING</li>
        <li class="nav-item">
            <a href="/superadmin/jam" class="nav-link {{ Request::is('superadmin/profil*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-clock"></i>
                <p>
                    Jam Masuk & Pulang
                </p>
            </a>
        </li>

        <li class="nav-item">
            <a href="/superadmin/rentang" class="nav-link {{ Request::is('superadmin/rentang*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-clock"></i>
                <p>
                    Rentang Jam Presensi
                </p>
            </a>
        </li>

        <li class="nav-item">
            <a href="/superadmin/libur" class="nav-link {{ Request::is('superadmin/profil*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-calendar"></i>
                <p>
                    Libur Nasional
                </p>
            </a>
        </li>

        <li class="nav-item">
            <a href="/superadmin/jenis" class="nav-link {{ Request::is('superadmin/profil*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-list"></i>
                <p>
                    Jenis Absen
                </p>
            </a>
        </li>
        {{--
        <li class="nav-item">
            <a href="/superadmin/generatetanggal"
                class="nav-link {{ Request::is('superadmin/generatetanggal') ? 'active' : '' }}">
                <i class="nav-icon fas fa-th"></i>
                <p>
                    Generate Presensi
                </p>
            </a>
        </li> --}}

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