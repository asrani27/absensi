<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>QRCODE</title>
    <style>
        .center {
                margin: auto;
                width: 60%;
                padding: 10px;
                text-align:center;
                }
                #divLoading {
  margin: 0px;
  display: none;
  padding: 0px;
  position: absolute;
  right: 0px;
  top: 0px;
  width: 100%;
  height: 100%;
  background-color: rgb(255, 255, 255);
  z-index: 30001;
  opacity: 0.8;}

#loading {
   position: absolute;
   color: White;
  top: 50%;
  left: 45%;}
    </style>
</head>
<body>    
    <div class="center">
        {!! QrCode::size(550)->generate($data); !!}
    </div>
</body>
<script>
    
</script>
</html>