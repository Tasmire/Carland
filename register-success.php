<?php

    $title = "Registration Successful"; //The page title

    require_once('./includes/layouts/header.php'); //Gets the header
    //require_once('./includes/db.php'); //Connect to the database

?>

<div class="alert alert-success" role="alert">
    Registration Successful! <a href="<?php echo $site_root ?>/login.php">Click here to login</a>
</div>

<?php
    require_once('./includes/layouts/footer.php');
?>