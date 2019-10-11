<?php 
if (isset($_SESSION['user_id']))
  redirect('gallery/index')?>
<?php require APPROOT . '/views/inc/header.php';?>
<div  class="row">
<div class="col-md-6 mx-auto">
  <div class="card card-body bg-light mt-5">
    <h2 class="text-center">Reset password</h2>
    <p class="text-center">Enter your camagru username, or the email address that you used to register. We'll send you an email with your username and a link to reset your password.</p>
    <form action="<?php echo URLROOT; ?>/users/recover" method="post">
      <div class="form-group">
        <label for="name">Email address or username: <sup class="text-danger">*</sup></label>
        <input type="name" name="name" class="form-control form-control-lg <?php echo (!empty($data['name_error'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>">
        <span class="invalid-feedback"><?php echo $data['name_error']; ?></span>
      </div>
      <div class="row">
        <div class="col">
          <input type="submit" value="SEND" class="btn btn-success btn-block">
        </div>
      </div>
    </form>
  </div>
</div>
</div>
<?php require APPROOT . '/views/inc/footer.php';?>