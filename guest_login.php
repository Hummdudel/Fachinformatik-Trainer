<?php
session_start();
$_SESSION['userid'] = "3";
$_SESSION['username'] = "Gast";
header("Location: startmenue.php");
?>