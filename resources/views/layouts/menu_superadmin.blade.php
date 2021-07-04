
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
    <a href="/superadmin/profil" class="nav-link {{ Request::is('superadmin/profil*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user"></i>
        <p>
            Profil
        </p>
    </a>
    </li>
    <li class="nav-item">
    <a href="/superadmin/skpd" class="nav-link {{ Request::is('superadmin/skpd*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-university"></i>
        <p>
        SKPD
        </p>
    </a>
    </li>
    <li class="nav-item">
    <a href="/superadmin/pegawai" class="nav-link {{ Request::is('superadmin/pegawai*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-users"></i>
        <p>
            Pegawai
        </p>
    </a>
    </li>
    
    <li class="nav-item has-treeview {{Request::is('superadmin/mutasi*') ? 'menu-open' : ''}}">
        <a href="#" class="nav-link {{Request::is('superadmin/mutasi*') ? 'active' : ''}}">
          <i class="nav-icon fas fa-people-arrows"></i>
          <p>
            Mutasi
            <i class="fas fa-angle-left right"></i>
          </p>
        </a>
        <ul class="nav nav-treeview" style="display: {{Request::is('superadmin/mutasi*') ? 'block' : 'none'}};">
            <li class="nav-item">
            <a href="/superadmin/mutasi/plt" class="nav-link {{ Request::is('superadmin/mutasi/plt*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user"></i>
                <p>PLT / PJ / PLH</p>
            </a>
            </li>
            <li class="nav-item">
            <a href="/superadmin/mutasi/pindah" class="nav-link {{ Request::is('superadmin/mutasi/pindah*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user"></i>
                <p>Pindah Jabatan</p>
            </a>
            </li>
            <li class="nav-item">
            <a href="/superadmin/mutasi/pensiun" class="nav-link {{ Request::is('superadmin/mutasi/pensiun*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user"></i>
                <p>Pensiun</p>
            </a>
            </li>
        </ul>
    </li>
    
    <li class="nav-item has-treeview {{Request::is('superadmin/kelas*') || Request::is('superadmin/pangkat*') || Request::is('superadmin/eselon*') || Request::is('superadmin/parameter*') ? 'menu-open' : ''}}">
        <a href="#" class="nav-link {{Request::is('superadmin/kelas*') || Request::is('superadmin/pangkat*') || Request::is('superadmin/eselon*') || Request::is('superadmin/parameter*') ? 'active' : ''}}">
          <i class="nav-icon fas fa-cogs"></i>
          <p>
            Setting
            <i class="fas fa-angle-left right"></i>
          </p>
        </a>
        <ul class="nav nav-treeview" style="display: {{Request::is('superadmin/kelas*') || Request::is('superadmin/pangkat*') || Request::is('superadmin/eselon*') || Request::is('superadmin/parameter*') ? 'block' : 'none'}};">
            <li class="nav-item">
            <a href="/superadmin/kelas" class="nav-link {{ Request::is('superadmin/kelas*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-list"></i>
                <p>Kelas Jabatan</p>
            </a>
            </li>
            <li class="nav-item">
            <a href="/superadmin/pangkat" class="nav-link {{ Request::is('superadmin/pangkat*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-list"></i>
                <p>Pangkat / Gol.</p>
            </a>
            </li>
            <li class="nav-item">
            <a href="/superadmin/eselon" class="nav-link {{ Request::is('superadmin/eselon*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-list"></i>
                <p>Eselon</p>
            </a>
            </li>
            <li class="nav-item">
            <a href="/superadmin/parameter" class="nav-link {{ Request::is('superadmin/parameter*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-list"></i>
                <p>Parameter</p>
            </a>
            </li>
        </ul>
    </li>
    
    <li class="nav-item has-treeview {{Request::is('superadmin/rekapitulasi*') ? 'menu-open' : ''}}">
        <a href="#" class="nav-link {{Request::is('superadmin/rekapitulasi*') ? 'active' : ''}}">
          <i class="nav-icon fas fa-file"></i>
          <p>
            Rekapitulasi
            <i class="fas fa-angle-left right"></i>
          </p>
        </a>
        <ul class="nav nav-treeview" style="display: {{Request::is('superadmin/rekapitulasi/pns*') ? 'block' : 'none'}};">
            <li class="nav-item">
            <a href="/superadmin/rekapitulasi/pns" class="nav-link {{ Request::is('superadmin/rekapitulasi/pns*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users"></i>
                <p>Data PNS</p>
            </a>
            </li>
            
        </ul>
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