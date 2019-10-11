<?php 
if (isset($_SESSION['user_id']))
  redirect('gallery/index')?>
<?php require APPROOT . '/views/inc/header.php';?>
<div  class="row">
    <div class="col-md-6 mx-auto">
      <div class="card card-body bg-light mt-5">
        <?php if (!empty($_SESSION['confirme_email']) || $data['is_verified'] == 'NO')
        {
          echo '<p class="text-danger text-center">Please check your email  to confirme your account</p>';
          unset($_SESSION['confirme_email']);
        } ?>
        <?php if (!empty($_SESSION['account_verif']))
        {
          echo '<p class="text-danger text-center">Please check your email  to confirme your account first</p>';
          unset($_SESSION['account_verif']);
        }?>
        <?php if (!empty($_SESSION['confirmed']))
        {
          echo '<p class="text-danger text-center">your email '. $_SESSION['confirmed'] . ' has been confirmed' .' <br> '. 'you can now sign in</p>';
          unset($_SESSION['confirmed']);
        }?>
        <?php if (!empty($_SESSION['recover']))
        {
          echo '<p class="text-danger text-center">A message has been sent to you by email with instructions on how to reset your password.</p>';
          unset($_SESSION['recover']);
        } ?>
         <?php if (!empty($_SESSION['restpassword_change']) && empty($_SESSION['recover']))
        {
          echo '<p class="text-success text-center">Password updated</p>';
          unset($_SESSION['restpassword_change']);

        }?>
        <h2>Sign in to your account</h2>
        <p>Please fill in your informations to sign in</p>
        <form action="<?php echo URLROOT; ?>/users/login" method="post">
          <div class="form-group">
            <label for="name">Username: <sup class="text-danger">*</sup></label>
            <input type="name" name="name" class="form-control form-control-lg <?php echo (!empty($data['name_error'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>">
            <span class="invalid-feedback"><?php echo $data['name_error']; ?></span>
          </div>
          <div class="form-group">
            <label for="password">Password: <sup class="text-danger">*</sup></label>
            <input type="password" name="password" class="form-control form-control-lg <?php echo (!empty($data['password_error'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
            <span class="invalid-feedback"><?php echo $data['password_error']; ?></span>
          </div>
          <div class="row">
            <div class="col pt-2">
              <input type="submit" value="Login" class="btn btn-success btn-block">
            </div>
            <div class="col pt-2">
              <a href="<?php echo URLROOT; ?>/users/register" class="btn btn-light btn-block">Sign Up</a>
            </div>
            <div class="col pt-2">
              <a href="<?php echo URLROOT; ?>/users/recover" class="btn btn-danger btn-block">Forgotten account?</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php require APPROOT . '/views/inc/footer.php';?>