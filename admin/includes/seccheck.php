<?php
session_start();

if (!isset($_SESSION['loggedin']) || !isset($_SESSION['staff']) || $_SESSION['staff'] != true) {
    header("location: ../404.php", 404);
}