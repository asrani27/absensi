
<nav class="mt-2">
<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    <li class="nav-item">
    <a href="/home/pegawai" class="nav-link {{ Request::is('home/pegawai') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>
        Beranda
        </p>
    </a>
    </li>

    <li class="nav-item">
    <a href="https://tpp.banjarmasinkota.go.id" class="nav-link">
        <i class="nav-icon fas fa-money-bill"></i>
        <p>
        TPP
        </p>
    </a>
    </li>

    <li class="nav-item">
    <a href="/pegawai/gantipass" class="nav-link {{Request::is('pegawai/gantipass') ? 'active' : ''}}">
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