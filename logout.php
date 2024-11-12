<?php
//Destroy the user's session
session_start();
session_unset();
header('location: ./');