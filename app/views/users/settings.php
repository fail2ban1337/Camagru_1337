<?php 
if (!isset($_SESSION['user_id']))
  redirect('users/login')?>
<?php require APPROOT . '/views/inc/header.php';?>
<div  class="row">
    <div class="col-md-6 mx-auto">
      <div class="card card-body bg-light mt-5">
      <?php if (!empty($_SESSION['delete_account']))
      {
        echo '<p class="text-success text-center">Check Your Mail To Remove Your Account</p>';
        unset($_SESSION['delete_account']);
      }?>
      <?php if (!empty($_SESSION['settings']))
        {
          echo '<p class="text-success text-center">Profile saved</p>';
          unset($_SESSION['settings']);
        } ?>
        <h2 class= "text-success">Edit your profile</h2>
        <form action="<?php echo URLROOT; ?>/users/settings" method="post">
          <div class="form-group">
            <label for="name">Username:</label>
            <input id="Settname" onkeyup="disableField()" type="name" name="name" class="form-control form-control-lg <?php echo (!empty($data['name_error'])) ? 'is-invalid' : ''; ?>" value="<?php echo $_SESSION['user_name']; ?>">
            <span class="invalid-feedback"><?php echo $data['name_error']; ?></span>
          </div>
          <div class="form-group">
            <label for="email">Email: </label>
            <input id="SettEmail" onkeyup="disableField()" type="email" name="email" class="form-control form-control-lg <?php echo (!empty($data['email_error'])) ? 'is-invalid' : ''; ?>" value="<?php echo $_SESSION['user_email']; ?>">
            <span class="invalid-feedback"><?php echo $data['email_error']; ?></span>
          </div>
          <div class="form-group">
            <label for="password">Confirm password: <sup class="text-danger">*</sup></label>
            <input id ="SettConf" type="password"  disabled name="password" class="form-control form-control-lg <?php echo (!empty($data['password_error']) && empty($data['email_error']) && empty($data['name_error'])) ? 'is-invalid' : ''; ?>" value="">
            <span class="invalid-feedback"><?php echo $data['password_error']; ?></span>
          </div>
          Notification email: <input type="checkbox" id="myCheck" onclick="myFunction()" <?php echo ($data['is_notifi'] == 1 ? 'checked' : 'unchecked') ?>>
          <script>
          var NameValue = "<?php echo $_SESSION['user_name']; ?>";
          var EmailValue = "<?php echo $_SESSION['user_email']; ?>";
          function disableField() {
          if (document.getElementById("Settname").value != NameValue || document.getElementById("SettEmail").value != EmailValue)
          {
              document.getElementById("SettConf").disabled = false;
              document.getElementById("btnconf").disabled = false;

          }else {
              document.getElementById("SettConf").disabled = true;
              document.getElementById("btnconf").disabled = true;
          }
          }

            function myFunction() {
            var checkBox = document.getElementById("myCheck");
            var request = new XMLHttpRequest();
            request.open('POST', 'http://localhost/abelomar/users/settings', true);
            if (checkBox.checked == true){
              var params = 'status='+'checked'
            } else {
              var params = 'status='+'unchecked';
            }
            request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            request.send(params);
          }
          </script>
          <div class="row">
            <div class="col pt-2">
              <input id ="btnconf"type="submit" value="SAVE PROFILE" class="btn btn-success btn-block" disabled>
            </div>
            <div class="col pt-2">
              <a href="<?php echo URLROOT; ?>" class="btn btn-light btn-block">CANCEL</a>
            </div>
            <div class="col pt-2">
              <a href="<?php echo URLROOT; ?>/users/passedit" class="btn bg-warning text-light btn-block">CHANGE PASSWORD</a>
            </div>
          </div>
        </form>
      </div>
      <div class="row">
        <div class="col text-center mt-5">
        <p class="text-justify"><sup class="text-danger">*</sup>in case you want delete your account you need to confirm that on your email</p>
        <a href="<?php echo URLROOT; ?>/users/removeaccount" class="btn text-light bg-danger btn-block">REMOVE YOUR ACCOUNT</a>
      </div>
    </div>
    </div>
  </div>
<?php require APPROOT . '/views/inc/footer.php';?>