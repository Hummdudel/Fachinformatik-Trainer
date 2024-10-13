<?php
include("security.php");
my_session_start();
my_session_regenerate_id();
session_destroy();
session_unset();
$_SESSION = "";

echo "<script> alert(\"Logout erfolgreich. Bis bald!\"); window.location.href = \"index.html\"; </script>";
?>