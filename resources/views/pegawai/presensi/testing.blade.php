
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Sample illustrating the use of Image Capture / Grab Frame - Take Photo.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Image Capture / Grab Frame - Take Photo Sample</title>
    <script>
      // Add a global error event listener early on in the page load, to help ensure that browsers
      // which don't support specific functionality still end up displaying a meaningful message.
      window.addEventListener('error', function(error) {
        if (ChromeSamples && ChromeSamples.setStatus) {
          console.error(error);
          ChromeSamples.setStatus(error.message + ' (Your browser may not support this feature.)');
          error.preventDefault();
        }
      });
    </script>
    
    
    
    
    <link rel="icon" href="../images/favicon.ico">
    
    <link rel="stylesheet" href="https://googlechrome.github.io/samples/styles/main.css">
    <link rel="stylesheet" href="https://googlechrome.github.io/samples/image-capture/grab-frame-take-photo.css">
    
  </head>

  <body>

<div id='results'>
  <div>
    <video autoplay></video>
    <button id='getUserMediaButton'>Get User Media</button>
  </div>
  <div>
    <canvas id='grabFrameCanvas'></canvas>
    <button id='grabFrameButton' disabled>Grab Frame</button>
  </div>
  <div>
    <canvas id='takePhotoCanvas'></canvas>
    <button id='takePhotoButton' disabled>Take Photo</button>
  </div>
</div>

<script>
  var ChromeSamples = {
    log: function() {
      var line = Array.prototype.slice.call(arguments).map(function(argument) {
        return typeof argument === 'string' ? argument : JSON.stringify(argument);
      }).join(' ');

      document.querySelector('#log').textContent += line + '\n';
    },

    clearLog: function() {
      document.querySelector('#log').textContent = '';
    },

    setStatus: function(status) {
      document.querySelector('#status').textContent = status;
    },

    setContent: function(newContent) {
      var content = document.querySelector('#content');
      while(content.hasChildNodes()) {
        content.removeChild(content.lastChild);
      }
      content.appendChild(newContent);
    }
  };
</script>


<script>
  if (/Chrome\/(\d+\.\d+.\d+.\d+)/.test(navigator.userAgent)){
    // Let's log a warning if the sample is not supposed to execute on this
    // version of Chrome.
    if (56 > parseInt(RegExp.$1)) {
      ChromeSamples.setStatus('Warning! Keep in mind this sample has been tested with Chrome ' + 56 + '.');
    }
  }
</script>




  
    
<script>
      var imageCapture;

function onGetUserMediaButtonClick() {
  navigator.mediaDevices.getUserMedia({video: true})
  .then(mediaStream => {
    document.querySelector('video').srcObject = mediaStream;

    const track = mediaStream.getVideoTracks()[0];
    imageCapture = new ImageCapture(track);
  })
  .catch(error => ChromeSamples.log(error));
}

function onGrabFrameButtonClick() {
  imageCapture.grabFrame()
  .then(imageBitmap => {
    const canvas = document.querySelector('#grabFrameCanvas');
    drawCanvas(canvas, imageBitmap);
  })
  .catch(error => ChromeSamples.log(error));
}

function onTakePhotoButtonClick() {
  imageCapture.takePhoto()
  .then(blob => createImageBitmap(blob))
  .then(imageBitmap => {
    const canvas = document.querySelector('#takePhotoCanvas');
    drawCanvas(canvas, imageBitmap);
  })
  .catch(error => ChromeSamples.log(error));
}

/* Utils */

function drawCanvas(canvas, img) {
  canvas.width = getComputedStyle(canvas).width.split('px')[0];
  canvas.height = getComputedStyle(canvas).height.split('px')[0];
  let ratio  = Math.min(canvas.width / img.width, canvas.height / img.height);
  let x = (canvas.width - img.width * ratio) / 2;
  let y = (canvas.height - img.height * ratio) / 2;
  canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
  canvas.getContext('2d').drawImage(img, 0, 0, img.width, img.height,
      x, y, img.width * ratio, img.height * ratio);
}

document.querySelector('video').addEventListener('play', function() {
  document.querySelector('#grabFrameButton').disabled = false;
  document.querySelector('#takePhotoButton').disabled = false;
});
</script>
    
<script>
  document.querySelector('#getUserMediaButton').addEventListener('click', onGetUserMediaButtonClick);
  document.querySelector('#grabFrameButton').addEventListener('click', onGrabFrameButtonClick);
  document.querySelector('#takePhotoButton').addEventListener('click', onTakePhotoButtonClick);
</script>

    
    <script>
      /* jshint ignore:start */
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
      ga('create', 'UA-53563471-1', 'auto');
      ga('send', 'pageview');
      /* jshint ignore:end */
    </script>
  </body>
</html>
