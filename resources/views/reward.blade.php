<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reward</title>
    <link rel="stylesheet" href="/theme/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/theme/dist/css/adminlte.min.css">
    <script src="/theme/plugins/jquery/jquery.min.js"></script>
    <script src="/theme/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/theme/dist/js/adminlte.min.js"></script>
    <style>
        .spinner {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 60px;
            font-size: 1.5rem;
            color: #666;
        }
    </style>
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container">
                    <h1 class="text-center text-dark text-bold">REWARD PRESENSI</h1>
                </div>
            </div>

            <div class="content">
                <div class="container">
                    <div class="row" id="reward-boxes">
                        <!-- Box 1 -->
                        <div class="col-lg-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-inf"><img src="/images/reward/number-1.png"></span>
                                <div class="info-box-content">
                                    <span class="box-name" style="font-size: 1.5rem;font-weight:bold">Nama
                                        1</span>
                                    <span class="info-box-number box-instansi">Instansi 1</span>
                                </div>
                            </div>
                            <!-- Box 2 -->
                            <div class="info-box">
                                <span class="info-box-icon bg-inf"><img src="/images/reward/number-2.png"></span>
                                <div class="info-box-content">
                                    <span class="box-name" style="font-size: 1.5rem;font-weight:bold">Nama
                                        2</span>
                                    <span class="info-box-number box-instansi">Instansi 2</span>
                                </div>
                            </div>
                            <!-- Box 3 -->
                            <div class="info-box">
                                <span class="info-box-icon bg-inf"><img src="/images/reward/number-3.png"></span>
                                <div class="info-box-content">
                                    <span class="box-name" style="font-size: 1.5rem;font-weight:bold">Nama
                                        3</span>
                                    <span class="info-box-number box-instansi">Instansi 3</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 text-center">
                            <br />
                            <a href="javascript:void(0)" id="spinBtn">
                                <img src="/images/reward/press.png" width="150rem">
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        $(function(){
  $("#spinBtn").on("click", function(){
    // ubah semua box jadi spinner/loading
    $(".box-name").html('<div class="spinner"><i class="fas fa-spinner fa-spin"></i></div>');
    $(".box-instansi").html("");

    $.get("{{ route('reward.spin.data') }}", function(data){
      if(data.length === 3){
        // tampilkan data bertahap
        data.forEach((item, index) => {
          setTimeout(() => {
            let box = $(".info-box").eq(index).find(".info-box-content");
            box.find(".box-name").text(item.nama);
            box.find(".box-instansi").text(item.skpd);
          }, (index+1) * 1000); // delay 1s, 2s, 3s
        });
      }
    });
  });
});
    </script>
</body>

</html>