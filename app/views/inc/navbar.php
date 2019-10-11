<?php
// session_start();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
      <?php if (isLoggedIn()) :  ?>
      <a class="navbar-brand" href="<?php echo URLROOT; ?>/gallery/index"><?php echo SITENAME; ?></a>
      <?php else : ?>
      <a class="navbar-brand" href="<?php echo URLROOT; ?>"><?php echo SITENAME; ?></a>
      <?php endif; ?>
      <button class="navbar-toggler" type="button" id="showtog" onclick="showtog()"data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
        <?php if (isLoggedIn()) :  ?>
          <li class="nav-item active pr-3">
            <a class="nav-link" href="<?php echo URLROOT; ?>/gallery/index"><i class="fas fa-home"></i> Home</a>
          </li>
          <li class="nav-item active pr-3">
            <a class="nav-link" href="<?php echo URLROOT; ?>/gallery/add"><i class="fas fa-camera-retro"></i> Camera </a>
          </li>
          <li class="nav-item active pr-3">
            <a class="nav-link" href="<?php echo URLROOT; ?>/gallery/profile"><i class="fas fa-user"></i> Profile </a>
          </li>
          <?php else : ?>
          <li class="nav-item active pr-3">
            <a class="nav-link" href="<?php echo URLROOT; ?>"><i class="fas fa-home"></i> Home</a>
          </li>
          <?php endif; ?>
        </ul>
        
        <ul class="navbar-nav ml-auto">
        <?php if(isset($_SESSION['user_id'])) : ?>
        <li class="nav-item active">
            <a class="nav-link" href="<?php echo URLROOT; ?>/users/settings"><i class="fas fa-users-cog"></i> Settings </a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="<?php echo URLROOT; ?>/users/logout/<?php echo $_SESSION['logout'];?>"><i class="fas fa-sign-out-alt"></i> Logout </a>
        </li>
        <?php else : ?>
          <li class="nav-item active pr-3">
            <a class="nav-link" href="<?php echo URLROOT; ?>/users/register"> Register</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="<?php echo URLROOT; ?>/users/login"> Login</a>
          </li>
        <?php endif; ?>
        </ul>
      </div>
    </nav>
