<?php
require_once(__DIR__ . '/../config.php');

header('Content-Type: text/html; charset=UTF-8');

if (session_id() == "") {
  session_start();
}

$_SESSION['loggedin'] = $_SESSION['loggedin'] ?? false;
$_SESSION['staff'] = $_SESSION['staff'] ?? false;
$home_nav = $home_nav ?? false;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="<?php echo $site_root ?>/static/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo $site_root ?>/static/css/theme.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
  <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
</head>

<body>
      <nav <?php if($home_nav): ?>
        class="nav-home"
        <?php else: ?>
        class="nav-default"
        <?php endif;?>>
        <a class="navbar-brand" href="<?php echo $site_root ?>/">
          <img src="<?php echo $site_root ?>/static/img/logos/carland-logo.png" class="logo-default" alt="Carland Logo"
          height="60px">
        </a>

        <!-- User is logged in -->
          <?php if ($_SESSION['loggedin']) : ?>

            <div class="nav-link dropdown">
              <?php echo $_SESSION['username'] ?>
              <div class="dropdown-content">
                <!-- Shows admin panel if user is an admin -->
                <?php if ($_SESSION['staff']) : ?>
                <a class="dropdown-item" href="<?php echo $site_root ?>/admin">Admin Panel</a>
                <?php endif; ?>

                <a class="dropdown-item" href="<?php echo $site_root ?>/wishlist.php">My Wishlist</a>
                <a class="dropdown-item text-danger" href="<?php echo $site_root ?>/logout.php">Logout</a>
              </div>
            </div>

          <!-- User is not logged in -->
          <?php else : ?>

          <a class="btn btn-danger" role="button" id="register" href="<?php echo $site_root ?>/register.php">Register</a>
          <a class="nav-link" href="<?php echo $site_root ?>/login.php">Log In</a>

          <?php endif; ?>
          <!-- End of $logged_in -->
        
        
        <a class="nav-link" href="<?php echo $site_root ?>/">Home</a>
      </nav>
    <div id="content">