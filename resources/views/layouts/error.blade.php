<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  {{-- <meta name="viewport" content="width=device-width, initial-scale=1"> --}}
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>PRESENSI</title>
  @include('layouts.css')
  @laravelPWA
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark navbar-lightblue">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link">@yield('title')</a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar  sidebar-light-lightblue elevation-4">
    <a href="#" class="brand-link navbar-lightblue">
      <img src="https://p.kindpng.com/picc/s/78-786207_user-avatar-png-user-avatar-icon-png-transparent.png" alt="User" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light text-white text-sm">
    </span>
    </a>
    <div class="sidebar">
    </div>
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
          @yield('content')
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>

  <footer class="main-footer">
    <div class="float-right d-none d-sm-inline">
    </div>
    <strong>Copyright &copy; 2021 Diskominfotik Banjarmasin</strong>
    <div class="float-right d-none d-sm-inline-block">
      
      
    </div>
  </footer>
</div>

@include('layouts.js')
</body>
</html>
