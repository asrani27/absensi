<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>PRESENSI</title>
  <!-- Tell the browser to be responsive to screen width -->

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="/theme/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="/theme/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="/theme/dist/css/adminlte.min.css">
  @toastr_css
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style>
      .login-page {
            background-image: url("/theme/bg3.jpg");
            background-size: cover;
            height:"10px";
            }
    </style>
    
@laravelPWA
</head>
<body class="hold-transition login-page">
<div class="login-box">
    
  <!-- /.login-logo -->
  @if((new \Jenssegers\Agent\Agent())->isMobile())
    @if((new \Jenssegers\Agent\Agent())->browser() == 'Safari')
    
    @else
    <button type="button" class="btn btn-info btn-lg btn-block" id="install-button"><strong><i class="fas fa-file-download"></i> Install APP</strong></button><br/>
    @endif
  @endif
  <div class="card">
    <div class="card-body login-card-body">
        
    <div class="login-logo" style="margin-bottom:0px;">
        <img src="/theme/logo.png" width="80px"><br/>
        <a href="#"><b>PRESENSI</b>
        </a>
    </div>
      <p class="login-box-msg"><b>PEMERINTAH KOTA BANJARMASIN</b></p>

      <form action="/login" method="post">
        @csrf
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="username" placeholder="NIP"  value="{{old('username')}}">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password"  value="{{old('password')}}">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <button type="submit" class='btn btn-block btn-primary'>Login</button>
      </form>

      {{-- <div class="social-auth-links text-center mb-3">
        <p>- OR -</p>
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
        </a>
      </div> --}}
      <!-- /.social-auth-links -->
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="/theme/plugins/jquery/jquery.min.js"></script>
<script src="/theme/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/theme/dist/js/adminlte.min.js"></script>

@toastr_js
@toastr_render
<script>
let installButton = document.getElementById('install-button');
let prompt;

window.addEventListener('beforeinstallprompt', function(e){
  e.preventDefault();
  prompt = e;
});

let installed = false;
installButton.addEventListener('click', async function(){
  prompt.prompt();
  let result = await prompt.userChoice;
  if (result&&result.outcome === 'accepted') {
     installed = true;
  }
})

window.addEventListener('appinstalled', async function(e) {
   installButton.style.display = "none";
});

let installable = true;
if (!('serviceWorker' in navigator)){
  installable = false;
}

//check when the page is loaded if it matches one of the PWA display modes
window.addEventListener('DOMContentLoaded', function(){
   if (navigator.standalone || window.matchMedia('(display-mode: standalone)').matches || window.matchMedia('(display-mode: fullscreen)').matches || window.matchMedia('(display-mode: minimal-ui)').matches) {
     installed = true;
    }
});

//but also add a listener. After app installation on desktop, the app will open in their own window right away.
window.addEventListener('DOMContentLoaded', function(){
   window.matchMedia('(display-mode: standalone)').addListener(function(e){
     if (e.matches) { installed = true;}
   });
   window.matchMedia('(display-mode: fullscreen)').addListener(function(e){
     if (e.matches) { installed = true;}
   });
   window.matchMedia('(display-mode: minimal-ui)').addListener(function(e){
     if (e.matches) { installed = true; }
   });
   
   if(installed) installButton.style.display = "none";

 });
 
</script>
</body>
</html>
