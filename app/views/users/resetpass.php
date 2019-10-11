<?php if (!empty($_SESSION['resetpass'])) :?>
<?php require APPROOT . '/views/inc/header.php';?>
<div  class="row">
    <div class="col-md-6 mx-auto">
      <div class="card card-body bg-light mt-5">
      <h2 class="text-center pb-3 text-success">RESET PASSWORD</h2>
        <form action="<?php echo URLROOT; ?>/users/resetpass" method="post">
          <div class="form-group">
            <label for="password">New Password: <sup class="text-danger">*</sup></label>
            <input type="password" name="first_pass" class="form-control form-control-lg <?php echo (!empty($data['first_pass_error'])) ? 'is-invalid' : ''; ?>" value="">
            <span class="invalid-feedback"><?php echo $data['first_pass_error']; ?></span>
          </div>
          <div class="form-group">
            <label for="password">Repate New Password: <sup class="text-danger">*</sup></label>
            <input type="password" name="secend_pass" class="form-control form-control-lg <?php echo (!empty($data['secend_pass_error'])) ? 'is-invalid' : ''; ?>" value="">
            <span class="invalid-feedback"><?php echo $data['secend_pass_error']; ?></span>
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
<?php endif; ?>