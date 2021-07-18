
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
    <a href="/admin/cuti" class="nav-link {{Request::is('admin/cuti') ? 'active' : ''}}">
        <i class="nav-icon fas fa-calendar"></i>
        <p>
        Cuti Pegawai
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