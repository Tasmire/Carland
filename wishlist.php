<?php
    session_start();

    //Redirect if the user is not logged in
    if (!$_SESSION['loggedin']) {
    header('location: login.php');
    exit();
    }

    $title = "My Wishlist"; //The page title

    require_once('./includes/layouts/header.php'); //Gets the header
    require_once('./includes/db.php'); //Connect to the database

?>

<h1 class="page-title">
    My Wishlist
</h1>

<?php

    //Add the user id as a filter for results
    $conditions[] = 'user_ID = ?';
    $parameters[] = $_SESSION['id'];

    require('./includes/search/results.php'); //Import the results

?>

<?php
    require_once('./includes/layouts/footer.php');
?>