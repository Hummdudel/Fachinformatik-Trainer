<?php
include("security.php");
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'strict');
ini_set('session.use_strict_mode', 1);
my_session_start();
my_session_regenerate_id();
$_SESSION['userid'] = "3";
$_SESSION['username'] = "Gast";
header("Location: startmenue.php");
?>