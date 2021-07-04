
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
    <li class="nav-item">
    <a href="/admin/profil" class="nav-link {{Request::is('admin/profil') ? 'active' : ''}}">
        <i class="nav-icon fas fa-user"></i>
        <p>
        Profil
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
    <a href="/admin/presensi" class="nav-link {{Request::is('admin/presensi') ? 'active' : ''}}">
        <i class="nav-icon fas fa-clock"></i>
        <p>
        Presensi
        </p>
    </a>
    </li>
    <li class="nav-item">
    <a href="/admin/jabatan" class="nav-link {{Request::is('admin/jabatan') ? 'active' : ''}}">
        <i class="nav-icon fas fa-map"></i>
        <p>
        Peta Jabatan
        </p>
    </a>
    </li>
    
    @if (Auth::user()->username == '1.02.01.')
        
    <li class="nav-item">
    <a href="/admin/rspuskesmas" class="nav-link {{Request::is('admin/rspuskesmas') ? 'active' : ''}}">
        <i class="nav-icon fas fa-hospital"></i>
        <p>
        RS & Puskesmas
        </p>
    </a>
    </li>
    @endif
    
    @if (Auth::user()->username == '1.01.01.')
        
    <li class="nav-item">
    <a href="/admin/sekolah" class="nav-link {{Request::is('admin/sekolah') ? 'active' : ''}}">
        <i class="nav-icon fas fa-graduation-cap"></i>
        <p>
            Sekolah
        </p>
    </a>
    </li>
    @endif

    @if (Auth::user()->hasRole('admin'))
        
    <li class="nav-item">
    <a href="/admin/plt" class="nav-link {{Request::is('admin/plt') ? 'active' : ''}}">
        <i class="nav-icon fas fa-th"></i>
        <p>
        PLT/PLH/PJ
        </p>
    </a>
    </li>
    
    <li class="nav-item">
    <a href="/admin/transfer" class="nav-link {{Request::is('admin/transfer') ? 'active' : ''}}">
        <i class="nav-icon fas fa-th"></i>
        <p>
        Transfer Pegawai
        </p>
    </a>
    </li>

    <li class="nav-item">
    <a href="/admin/pensiun" class="nav-link {{Request::is('admin/pensiun') ? 'active' : ''}}">
        <i class="nav-icon fas fa-th"></i>
        <p>
        Pensiun
        </p>
    </a>
    </li>
    @endif
    <li class="nav-item">
    <a href="/admin/org" class="nav-link" target="_blank">
        <i class="nav-icon fas fa-sitemap"></i>
        <p>
        Struktur Organisasi
        </p>
    </a>
    </li>
    <li class="nav-item">
    <a href="/admin/rekapitulasi" class="nav-link {{Request::is('admin/rekapitulasi') ? 'active' : ''}}">
        <i class="nav-icon fas fa-file"></i>
        <p>
        Cetak TPP
        </p>
    </a>
    </li>
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