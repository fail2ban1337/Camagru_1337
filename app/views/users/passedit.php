<?php 
if (!isset($_SESSION['user_id']))
  redirect('users/login')?>
<?php require APPROOT . '/views/inc/header.php';?>
<div  class="row">
    <div class="col-md-6 mx-auto">
      <div class="card card-body bg-light mt-5">
      <?php if (!empty($_SESSION['password_change']))
        {
          echo '<p class="text-success text-center">Password updated</p>';
          unset($_SESSION['password_change']);
        } ?>
      <h2 class="text-center pb-3 text-success">Change your password</h2>
        <form action="<?php echo URLROOT; ?>/users/passedit" method="post">
          <div class="form-group">
            <label for="name">Current password: <sup class="text-danger">*</sup></label>
            <input type="password" name="password" class="form-control form-control-lg <?php echo (!empty($data['password_error'])) ? 'is-invalid' : ''; ?>" value="<?php echo (empty($data['password_error'])) ? $data['password'] : ''; ?>">
            <span class="invalid-feedback"><?php echo $data['password_error']; ?></span>
          </div>
          <div class="form-group">
            <label for="password">New Password: <sup class="text-danger">*</sup></label>
            <input type="password" name="first_conpass" class="form-control form-control-lg <?php echo (!empty($data['first_conpass_error'])) ? 'is-invalid' : ''; ?>" value="">
            <span class="invalid-feedback"><?php echo $data['first_conpass_error']; ?></span>
          </div>
          <div class="form-group">
            <label for="password">Repate New Password: <sup class="text-danger">*</sup></label>
            <input type="password" name="secend_conpass" class="form-control form-control-lg <?php echo (!empty($data['secend_conpass_error'])) ? 'is-invalid' : ''; ?>" value="">
            <span class="invalid-feedback"><?php echo $data['secend_conpass_error']; ?></span>
          </div>
          <div class="row">
            <div class="col pt-2">
              <input type="submit" value="SET NEW PASSWORD" class="btn btn-success btn-block">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php require APPROOT . '/views/inc/footer.php';?>