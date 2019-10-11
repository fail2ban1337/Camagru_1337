<?php
  if (!isset($_SESSION['user_id']))
  redirect('users/login')?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
  <script src="https://kit.fontawesome.com/d4d317a665.js"></script>
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/style.css">
  <title><?php echo SITENAME; ?></title>
</head>
<body>
  <?php require APPROOT . '/views/inc/navbar.php'; ?>
  <div class="container-fluid">
    <div class="jumbotron text-center">
      <h1><?php echo SITENAME; ?></h1>
      <p>Studio Camera</p>
    </div>
      <div class="parent-container d-flex">
      <div class="card card-body mb-5">
        <div class="container ">
          <div class="row text-center justify-content-center">
            <div class="col-sm-8">
                <video class="img-fluid" id="video"></video>
            </div>
          </div>
          <div class="row text-center justify-content-center">
            <div class="col-sm-6">
                    <button type="button" id="startbutton" class="btn btn-sm btn-block btn-primary mt-auto mr-1" disabled>Get started</button>
            </div>
          </div>
              <div class="row text-center justify-content-center mt-3">
                <div class="col-sm-6">
                <input type="file" accept="image/x-png,image/gif,image/jpeg" style="display:none;" id="file" name="file"/>
                <input class ="bg-primary border-0" id = "browse" type="button" disabled id="loadFileXml" value="Browse" onclick="document.getElementById('file').click();"/>
                </div>
              </div>
            <div class="row no-gutters text-center justify-content-center">
              <div class="col-md-2 col-3">
                  <img id ="sticker1" src="<?php echo URLROOT; ?>/public/img/sticker1.png" class="img-fluid">
              </div>
              <div class="col-md-2 col-3">
                  <img id ="sticker2" src="<?php echo URLROOT; ?>/public/img/sticker2.png" class="img-fluid">
              </div>
              <div class="col-md-2 col-3">
                  <img id ="sticker3" src="<?php echo URLROOT; ?>/public/img/sticker3.png" class="img-fluid" >
              </div>
            </div>
            <div class="row justify-content-center">
              <div class="col-sm-8">
                <img  id = "imageoncanv" src="" style="position:absolute;width:30%; height:auto;display:none" alt="">
                <canvas class="img-fluid" id="canvas"></canvas>
              </div>
            </div>
            <div class="row justify-content-center text-center">
              <div class="col-sm-12">
                  <form action="<?php echo URLROOT; ?>/gallery/add" method="post" enctype="multipart/form-data" name ="form1">
                      <input name="hidden_data" id='hidden_data' type="hidden"/>
                      <input name="sticker_data" id='stickerdata' type="hidden"/>
                      <input type="hidden" id="upload" value ="upload" class="btn btn-primary btn-sm">
                  </form>
              </div>
            </div>
        </div>
        </div>
    <div class="container">
        <?php $numOfCols = 1;
          $rowCount = 1;
          $bootstrapCoolWidth = 12 / $numOfCols;
          ?>
          <div class="card card-body mb-3">
          <div class="row justify-content-center text-center">
            <?php foreach($data as $result) {?>
              <div class="justify-content-center text-center mb-3 col-md-<?php echo $bootstrapCoolWidth;?>">
                <form action="<?php echo URLROOT; ?>/gallery/add" method="post">
                <img  src="<?php echo URLROOT; ?>/public/uploded/<?php echo $result->photoname?>.png" class="img-fluid">
                <?php echo '<input type="hidden" value="'.$result->photoname.'" name="delete_file"/>';?>
                <input type="submit" value="delete" class="btn btn-danger btn-block">
              </div>
              </form>
              <?php if ($rowCount % $numOfCols == 0)echo '</div><div class="row">';
            }?>
          </div>
          </div>
        </div>
      </div>
      <script>
      if ( window.history.replaceState )
      {
        window.history.replaceState( null, null, window.location.href );
      }
        document.getElementById("sticker1").addEventListener("click", function(){
        var element = document.getElementById('startbutton');
        var browse = document.getElementById('browse');
        var elementimg = document.getElementById('imageoncanv');
        elementimg.src = '<?php echo URLROOT; ?>/public/img/sticker1.png';
        element.removeAttribute("disabled");
        browse.removeAttribute("disabled");
        document.getElementById('stickerdata').value = 'sticker1.png';
        });
        document.getElementById('sticker2').addEventListener("click", function(){
        var element = document.getElementById('startbutton');
        var browse = document.getElementById('browse');
        var elementimg = document.getElementById('imageoncanv');
        elementimg.src = '<?php echo URLROOT; ?>/public/img/sticker2.png';
        element.removeAttribute("disabled");
        browse.removeAttribute("disabled");
        document.getElementById('stickerdata').value = 'sticker2.png';
        });
        document.getElementById('sticker3').addEventListener("click", function(){
        var element = document.getElementById('startbutton');
        var browse = document.getElementById('browse');
        var elementimg = document.getElementById('imageoncanv');
        elementimg.src = '<?php echo URLROOT; ?>/public/img/sticker3.png';
        element.removeAttribute("disabled");
        browse.removeAttribute("disabled");
        document.getElementById('stickerdata').value = 'sticker3.png';
        });

        document.getElementById('startbutton').addEventListener("click", function()
        {
        var element = document.getElementById('imageoncanv');
        element.style.display = '';
        });

        document.getElementById('file').addEventListener("change", function()
        {
        var element = document.getElementById('imageoncanv');
        element.style.display = '';
        });

        document.getElementById('file').onchange = function(e) {
        var img = new Image();
        img.onload = draw;
        img.onerror = failed;
        /// URL.createObjectURL methode to create a symlink directtly to the file on disk
        if (this.files[0]) {
        img.src = URL.createObjectURL(this.files[0]);
        if ( /\.(png|jpeg|jpg)$/i.test(this.files[0].name) === false ) { var element = document.getElementById('imageoncanv');
        element.style.display = 'none';
        }};
        };
        function draw() {
          if (this.width >= 720)
          {
            var max_size = 720;
            var w = this.width;
            var h = this.height;
            if (w > h) {  if (w > max_size) { h*=max_size/w; w=max_size; }
            } else     {  if (h > max_size) { w*=max_size/h; h=max_size; } }
            var canvas = document.getElementById('canvas');
            canvas.width = w;
            canvas.height = h;
            canvas.getContext('2d').drawImage(this, 0, 0, w, h);
          }else {
            var canvas = document.getElementById('canvas');
            canvas.width = 320;
            canvas.height = 320;
            canvas.getContext('2d').drawImage(this, 0, 0, canvas.width, canvas.height);
          }
            var dataURL = canvas.toDataURL("image/png");
            document.getElementById('hidden_data').value = dataURL;
            document.getElementById('upload').setAttribute('type','submit');
        }
        function failed() {
        var element = document.getElementById('imageoncanv');
        element.style.display = 'none';
        return false;
        };
      </script>
    <script src="<?php echo URLROOT; ?>/public/js/camera.js"></script>
    <?php require APPROOT . '/views/inc/footer.php';?>
