(function() {

    var streaming = false,
        video        = document.querySelector('#video'),
        canvas       = document.querySelector('#canvas'),
        startbutton  = document.querySelector('#startbutton'),
        width = 720,
        height = 0;

    navigator.getMedia = ( navigator.getUserMedia ||
                           navigator.webkitGetUserMedia ||
                           navigator.mediaDevices.getUserMedia ||
                           navigator.msGetUserMedia);



    if (navigator.mediaDevices.getUserMedia) {
    navigator.mediaDevices.getUserMedia({  audio: false, video: true })
    .then(function (stream) {
      try {
        video.src = window.URL.createObjectURL(stream);
      } catch (error) {
        video.srcObject = stream;
      }
      video.play();
     })
     .catch(function (e) { return false; });
}
else {
    navigator.getMedia(
      {
        video: true,
        audio: false
      },
      function(stream) {

          video.srcObject=stream;
        video.play();
      },
      function(err) {
        return;
      }
    );
    }
    video.addEventListener('canplay', function(ev){
      if (!streaming) {
        height = video.videoHeight / (video.videoWidth/width);
        video.setAttribute('width', width);
        video.setAttribute('height', height);
        canvas.setAttribute('width', width);
        canvas.setAttribute('height', height);
        streaming = true;
      }
    }, false);
    function takepicture() {
      canvas.width = width;
      canvas.height = height;
      canvas.getContext('2d').drawImage(video, 0, 0, width, height);
      document.getElementById('file').value = "";
      var dataURL = canvas.toDataURL("image/png");
          document.getElementById('hidden_data').value = dataURL;
      document.getElementById('upload').setAttribute('type','submit');
    }
    startbutton.addEventListener('click', function(ev){
        takepicture();
      ev.preventDefault();
    }, false);

  })();