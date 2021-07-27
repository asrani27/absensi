
<!doctype html>
<!--
Copyright 2021 Google Inc. All Rights Reserved.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

  http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
-->
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
    
    <h1>Image Capture / Grab Frame - Take Photo Sample</h1>
    <p class="availability">
      Available in <a target="_blank" href="https://www.chromestatus.com/feature/4843864737185792">Chrome 56+</a> |
      <a target="_blank" href="https://github.com/googlechrome/samples/blob/gh-pages/image-capture/grab-frame-take-photo.html">View on GitHub</a> |
      <a  href="index.html">Browse Samples</a>
    </p>
    <h3>Background</h3>
<p>The ImageCapture Web API allows web developers to capture images from camera
in the form of a Blob with <code>takePhoto()</code> or as a ImageBitmap with
<code>grabFrame()</code>.</p>
<img src="data:image/png;base64,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">
<div id='results'>
  <div>
    <video autoplay></video>
    <button id='getUserMediaButton'>Get User Media</button>
  </div>
  <div>
    <canvas id='grabFrameCanvas'></canvas>
    <canvas id='grabFrameCanvas2'></canvas>
    <button id='grabFrameButton' disabled>Grab Frame</button>
  </div>
  <div>
    <form action="/savephoto" method="POST">
      @csrf
      <input type="text" id="photo" value="" name="photo">
      <button type="submit">Save Photo</button>
    </form>
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

<h3>Live Output</h3>
<div id="output" class="output">
  <div id="content"></div>
  <div id="imageBitmap"></div>
  <div id="canvas"></div>
  <div id="status"></div>
  <pre id="log"></pre>
</div>


<script>
  if (/Chrome\/(\d+\.\d+.\d+.\d+)/.test(navigator.userAgent)){
    // Let's log a warning if the sample is not supposed to execute on this
    // version of Chrome.
    if (56 > parseInt(RegExp.$1)) {
      ChromeSamples.setStatus('Warning! Keep in mind this sample has been tested with Chrome ' + 56 + '.');
    }
  }
</script>




  
    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>

var imageCapture;
$(document).ready(function(){
  navigator.mediaDevices.getUserMedia({video: true})
  .then(mediaStream => {
    document.querySelector('video').srcObject = mediaStream;

    const track = mediaStream.getVideoTracks()[0];
    imageCapture = new ImageCapture(track);
  })
  .catch(error => ChromeSamples.log(error));
});

// function onGetUserMediaButtonClick() {
//   navigator.mediaDevices.getUserMedia({video: true})
//   .then(mediaStream => {
//     document.querySelector('video').srcObject = mediaStream;

//     const track = mediaStream.getVideoTracks()[0];
//     imageCapture = new ImageCapture(track);
//   })
//   .catch(error => ChromeSamples.log(error));
// }

function onGrabFrameButtonClick() {
  imageCapture.grabFrame()
  .then(imageBitmap => {
    const canvas = document.querySelector('#grabFrameCanvas');
    drawCanvas(canvas, imageBitmap);
    var canvase = document.getElementById('grabFrameCanvas');
    var dataURL = canvase.toDataURL();
    document.getElementById("imageBitmap").innerHTML = dataURL;
    document.getElementById("photo").value = dataURL;
    console.log(dataURL);
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
//  document.querySelector('#takePhotoButton').disabled = false;
});
</script>
    
  

  
    <h3>JavaScript Snippet</h3>
  

  
    <figure class="highlight"><pre><code class="language-js" data-lang="js"><span class="kd">var</span> <span class="nx">imageCapture</span><span class="p">;</span>

<span class="kd">function</span> <span class="nx">onGetUserMediaButtonClick</span><span class="p">()</span> <span class="p">{</span>
  <span class="nb">navigator</span><span class="p">.</span><span class="nx">mediaDevices</span><span class="p">.</span><span class="nx">getUserMedia</span><span class="p">({</span><span class="na">video</span><span class="p">:</span> <span class="kc">true</span><span class="p">})</span>
  <span class="p">.</span><span class="nx">then</span><span class="p">(</span><span class="nx">mediaStream</span> <span class="o">=&gt;</span> <span class="p">{</span>
    <span class="nb">document</span><span class="p">.</span><span class="nx">querySelector</span><span class="p">(</span><span class="dl">'</span><span class="s1">video</span><span class="dl">'</span><span class="p">).</span><span class="nx">srcObject</span> <span class="o">=</span> <span class="nx">mediaStream</span><span class="p">;</span>

    <span class="kd">const</span> <span class="nx">track</span> <span class="o">=</span> <span class="nx">mediaStream</span><span class="p">.</span><span class="nx">getVideoTracks</span><span class="p">()[</span><span class="mi">0</span><span class="p">];</span>
    <span class="nx">imageCapture</span> <span class="o">=</span> <span class="k">new</span> <span class="nx">ImageCapture</span><span class="p">(</span><span class="nx">track</span><span class="p">);</span>
  <span class="p">})</span>
  <span class="p">.</span><span class="k">catch</span><span class="p">(</span><span class="nx">error</span> <span class="o">=&gt;</span> <span class="nx">ChromeSamples</span><span class="p">.</span><span class="nx">log</span><span class="p">(</span><span class="nx">error</span><span class="p">));</span>
<span class="p">}</span>

<span class="kd">function</span> <span class="nx">onGrabFrameButtonClick</span><span class="p">()</span> <span class="p">{</span>
  <span class="nx">imageCapture</span><span class="p">.</span><span class="nx">grabFrame</span><span class="p">()</span>
  <span class="p">.</span><span class="nx">then</span><span class="p">(</span><span class="nx">imageBitmap</span> <span class="o">=&gt;</span> <span class="p">{</span>
    <span class="kd">const</span> <span class="nx">canvas</span> <span class="o">=</span> <span class="nb">document</span><span class="p">.</span><span class="nx">querySelector</span><span class="p">(</span><span class="dl">'</span><span class="s1">#grabFrameCanvas</span><span class="dl">'</span><span class="p">);</span>
    <span class="nx">drawCanvas</span><span class="p">(</span><span class="nx">canvas</span><span class="p">,</span> <span class="nx">imageBitmap</span><span class="p">);</span>
  <span class="p">})</span>
  <span class="p">.</span><span class="k">catch</span><span class="p">(</span><span class="nx">error</span> <span class="o">=&gt;</span> <span class="nx">ChromeSamples</span><span class="p">.</span><span class="nx">log</span><span class="p">(</span><span class="nx">error</span><span class="p">));</span>
<span class="p">}</span>

<span class="kd">function</span> <span class="nx">onTakePhotoButtonClick</span><span class="p">()</span> <span class="p">{</span>
  <span class="nx">imageCapture</span><span class="p">.</span><span class="nx">takePhoto</span><span class="p">()</span>
  <span class="p">.</span><span class="nx">then</span><span class="p">(</span><span class="nx">blob</span> <span class="o">=&gt;</span> <span class="nx">createImageBitmap</span><span class="p">(</span><span class="nx">blob</span><span class="p">))</span>
  <span class="p">.</span><span class="nx">then</span><span class="p">(</span><span class="nx">imageBitmap</span> <span class="o">=&gt;</span> <span class="p">{</span>
    <span class="kd">const</span> <span class="nx">canvas</span> <span class="o">=</span> <span class="nb">document</span><span class="p">.</span><span class="nx">querySelector</span><span class="p">(</span><span class="dl">'</span><span class="s1">#takePhotoCanvas</span><span class="dl">'</span><span class="p">);</span>
    <span class="nx">drawCanvas</span><span class="p">(</span><span class="nx">canvas</span><span class="p">,</span> <span class="nx">imageBitmap</span><span class="p">);</span>
  <span class="p">})</span>
  <span class="p">.</span><span class="k">catch</span><span class="p">(</span><span class="nx">error</span> <span class="o">=&gt;</span> <span class="nx">ChromeSamples</span><span class="p">.</span><span class="nx">log</span><span class="p">(</span><span class="nx">error</span><span class="p">));</span>
<span class="p">}</span>

<span class="cm">/* Utils */</span>

<span class="kd">function</span> <span class="nx">drawCanvas</span><span class="p">(</span><span class="nx">canvas</span><span class="p">,</span> <span class="nx">img</span><span class="p">)</span> <span class="p">{</span>
  <span class="nx">canvas</span><span class="p">.</span><span class="nx">width</span> <span class="o">=</span> <span class="nx">getComputedStyle</span><span class="p">(</span><span class="nx">canvas</span><span class="p">).</span><span class="nx">width</span><span class="p">.</span><span class="nx">split</span><span class="p">(</span><span class="dl">'</span><span class="s1">px</span><span class="dl">'</span><span class="p">)[</span><span class="mi">0</span><span class="p">];</span>
  <span class="nx">canvas</span><span class="p">.</span><span class="nx">height</span> <span class="o">=</span> <span class="nx">getComputedStyle</span><span class="p">(</span><span class="nx">canvas</span><span class="p">).</span><span class="nx">height</span><span class="p">.</span><span class="nx">split</span><span class="p">(</span><span class="dl">'</span><span class="s1">px</span><span class="dl">'</span><span class="p">)[</span><span class="mi">0</span><span class="p">];</span>
  <span class="kd">let</span> <span class="nx">ratio</span>  <span class="o">=</span> <span class="nb">Math</span><span class="p">.</span><span class="nx">min</span><span class="p">(</span><span class="nx">canvas</span><span class="p">.</span><span class="nx">width</span> <span class="o">/</span> <span class="nx">img</span><span class="p">.</span><span class="nx">width</span><span class="p">,</span> <span class="nx">canvas</span><span class="p">.</span><span class="nx">height</span> <span class="o">/</span> <span class="nx">img</span><span class="p">.</span><span class="nx">height</span><span class="p">);</span>
  <span class="kd">let</span> <span class="nx">x</span> <span class="o">=</span> <span class="p">(</span><span class="nx">canvas</span><span class="p">.</span><span class="nx">width</span> <span class="o">-</span> <span class="nx">img</span><span class="p">.</span><span class="nx">width</span> <span class="o">*</span> <span class="nx">ratio</span><span class="p">)</span> <span class="o">/</span> <span class="mi">2</span><span class="p">;</span>
  <span class="kd">let</span> <span class="nx">y</span> <span class="o">=</span> <span class="p">(</span><span class="nx">canvas</span><span class="p">.</span><span class="nx">height</span> <span class="o">-</span> <span class="nx">img</span><span class="p">.</span><span class="nx">height</span> <span class="o">*</span> <span class="nx">ratio</span><span class="p">)</span> <span class="o">/</span> <span class="mi">2</span><span class="p">;</span>
  <span class="nx">canvas</span><span class="p">.</span><span class="nx">getContext</span><span class="p">(</span><span class="dl">'</span><span class="s1">2d</span><span class="dl">'</span><span class="p">).</span><span class="nx">clearRect</span><span class="p">(</span><span class="mi">0</span><span class="p">,</span> <span class="mi">0</span><span class="p">,</span> <span class="nx">canvas</span><span class="p">.</span><span class="nx">width</span><span class="p">,</span> <span class="nx">canvas</span><span class="p">.</span><span class="nx">height</span><span class="p">);</span>
  <span class="nx">canvas</span><span class="p">.</span><span class="nx">getContext</span><span class="p">(</span><span class="dl">'</span><span class="s1">2d</span><span class="dl">'</span><span class="p">).</span><span class="nx">drawImage</span><span class="p">(</span><span class="nx">img</span><span class="p">,</span> <span class="mi">0</span><span class="p">,</span> <span class="mi">0</span><span class="p">,</span> <span class="nx">img</span><span class="p">.</span><span class="nx">width</span><span class="p">,</span> <span class="nx">img</span><span class="p">.</span><span class="nx">height</span><span class="p">,</span>
      <span class="nx">x</span><span class="p">,</span> <span class="nx">y</span><span class="p">,</span> <span class="nx">img</span><span class="p">.</span><span class="nx">width</span> <span class="o">*</span> <span class="nx">ratio</span><span class="p">,</span> <span class="nx">img</span><span class="p">.</span><span class="nx">height</span> <span class="o">*</span> <span class="nx">ratio</span><span class="p">);</span>
<span class="p">}</span>

<span class="nb">document</span><span class="p">.</span><span class="nx">querySelector</span><span class="p">(</span><span class="dl">'</span><span class="s1">video</span><span class="dl">'</span><span class="p">).</span><span class="nx">addEventListener</span><span class="p">(</span><span class="dl">'</span><span class="s1">play</span><span class="dl">'</span><span class="p">,</span> <span class="kd">function</span><span class="p">()</span> <span class="p">{</span>
  <span class="nb">document</span><span class="p">.</span><span class="nx">querySelector</span><span class="p">(</span><span class="dl">'</span><span class="s1">#grabFrameButton</span><span class="dl">'</span><span class="p">).</span><span class="nx">disabled</span> <span class="o">=</span> <span class="kc">false</span><span class="p">;</span>
  <span class="nb">document</span><span class="p">.</span><span class="nx">querySelector</span><span class="p">(</span><span class="dl">'</span><span class="s1">#takePhotoButton</span><span class="dl">'</span><span class="p">).</span><span class="nx">disabled</span> <span class="o">=</span> <span class="kc">false</span><span class="p">;</span>
<span class="p">});</span></code></pre></figure>
  



<script>
  // document.querySelector('#getUserMediaButton').addEventListener('click', onGetUserMediaButtonClick);
  document.querySelector('#grabFrameButton').addEventListener('click', onGrabFrameButtonClick);
 // document.querySelector('#takePhotoButton').addEventListener('click', onTakePhotoButtonClick);
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
