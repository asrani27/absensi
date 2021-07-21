<!doctype html>
<html>
<head>
<meta charset="UTF-8"/>
<title>Show page loading spinner while processing a form</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" />
<link href="http://www.marcorpsa.com/ee/css/custom.css" rel="stylesheet" />
<style type="text/css">
#loader {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  width: 100%;
  background: rgba(0,0,0,0.75) url(images/loading2.gif) no-repeat center center;
  z-index: 10000;
}
</style>
</head>
<body>
<div class="wrapper">
  <header>
    <div class="container">
    <h1 class="col-lg-9">Show page loading spinner while processing a form</h1>
    
    </div>
  </header>
  <div class="container">
    <h5>Author: Julian Hansen, March 2017</h5>
<form class="form horizontal">
  <div class="row">
    <label class="col-xs-4">Your Name:</label>
    <div class="col-xs-8">
      <input type="input" class="form-control" />
    </div>
  </div>
  <button class="btn btn-primary" type="submit">Submit</button>
</form>
</div>
</div>
<footer>
  <div class="container">
    Copyright Julian Hansen &copy; 2016
  </div>
</footer>
<div id="loader"></div>
<!-- INCLUDE "t2228.php:PHP" -->
 
<script src="http://code.jquery.com/jquery.js"></script>
 
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script>
var spinner = $('#loader');
$(function() {
  $('form').submit(function(e) {
    e.preventDefault();
    spinner.show();
  });
});
</script>
</body>
</html>