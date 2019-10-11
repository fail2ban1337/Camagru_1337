<?php require APPROOT . '/views/inc/header.php';?>
<div class="jumbotron text-center">
  <h1>Camgaru </h1>
  <p>Home Gallery to show all images</p>
</div>
       <div class="container">
       <?php $indice = 0; foreach ($data['data'] as $result) { 
         $data['likestb'][$indice];
         ?>
         <div class="row justify-content-center mb-5">
           <div class="card card-body col-md-6 bg-light">
            <h4 class="text-uppercase small"><?php echo $result->name?> :</h4>
             <img class="img-fluid border rounded-top" src="<?php echo URLROOT; ?>/public/uploded/<?php echo $result->photoname?>.png">
             <div class="cardbox-comments mt-2" id ="<?php echo $result->photoname?>card">
             <?php if (isLoggedIn()): ?>          
               <textarea id="i" class="form-control w-100 mb-2" placeholder="write a comment..." rows="1" style="resize: none;" maxlength="100"></textarea>
               <?php echo '<button  id="'.$result->photoname.'" class="btn" onclick="like('.$result->photoname.')"  name=""><span>'.($result->likes > 0 ? $result->likes : '').'</span><i class="fas fa-heart"'.( $data['likestb'][$indice] === "Y" ? 'style="color:red"' : '').'></i></button>';?>
               <?php echo '<button name="" class="btn" onclick="comment('.$result->photoname.')"><i class="fas fa-sign-in-alt"></i></button>' ?>
               <br/>
               <?php endif ?>
             </div>
          <?php foreach ($data['comments'][$indice] as $comment) { 
            if ($result->photoname === $comment->photoname) {?>
           <div id="comment_list">
               <hr style="margin-top: 0.2rem;margin-bottom: 0.5rem;">
               <strong style="color:green"><?php echo $comment->name.": ";?></strong><?php  echo $comment->comments;?>
           </div>
           <?php } }?> 
           </div>
         </div>
         <?php  $indice++; }?>
       </div>
       <div class="row justify-content-center mb-5">
       <nav aria-label="Page navigation example">
      <ul class="pagination">
        <li class="page-item"><a class="page-link" href="<?php echo URLROOT; ?>/gallery/index/<?php echo $data['pages'][0]?>">Previous</a></li>
        <li class="page-item"><a class="page-link" href="<?php echo URLROOT; ?>/gallery/index/<?php echo $data['pages'][1]?>">Next</a></li>
      </ul>
    </nav>
    </div>
       <?php if (isLoggedIn()): ?>
       <script>
       function escapeHtml(text) {
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
      }
        function comment(id)
        {
          var x = document.getElementById(id).parentNode.firstElementChild.value;
          x = x.trim();        
          var xhttp = new XMLHttpRequest();
          xhttp.open('POST', 'http://localhost/abelomar/gallery/index', true);
          xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
          if (this.responseText === 'the comment haas been added')
          {
            
            var x = document.getElementById(id+'card');
            var ele = document.createElement("div");
            ele.innerHTML =`
            <hr style="margin-top: 0.2rem;margin-bottom: 0.5rem;">
            <strong style="color:green"><?php echo $_SESSION['user_name'].": ";?>
            </strong>`+escapeHtml(document.getElementById(id).parentNode.firstElementChild.value);
            ele.id = "comment_list";
            document.getElementById(id).parentNode.firstElementChild.value = '';
            x.parentNode.insertBefore(ele, x.nextSibling);
          }else {
            // something goes wrong on the database side
          }
          }
          }
          xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
          var params = "commentofthephoto="+x+"&photoname="+id;

          xhttp.send(params);          
        }

       function like(id) {
        var request = new XMLHttpRequest();
        request.open('POST', 'http://localhost/abelomar/gallery/index', true);
        request.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            var c = document.getElementById(id).children;
              x = c[0].textContent;
              if (this.responseText === 'the like hasss ben added')
              {
                c[0].textContent = ++x;
                c[1].style.color = "red";
              }else {
              if (x == 1)
              {
                c[0].textContent = '';
                c[1].style.color = null;
              }else {
                c[0].textContent = --x;
                c[1].style.color = null;
              }
              }
          }
        }
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        var params = "likephotoname="+id;
        request.send(params);
       }
       </script>
       <?php endif ?>
<?php require APPROOT . '/views/inc/footer.php';?>
