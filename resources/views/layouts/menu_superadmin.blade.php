
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