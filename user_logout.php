<?php
include("security.php");
my_session_start();
session_destroy();
session_unset();
$_SESSION = "";
my_session_regenerate_id();

echo "<script> alert(\"Logout erfolgreich. Bis bald!\"); window.location.href = \"index.html\"; </script>";
?>