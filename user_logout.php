<?php
session_start();
session_destroy();
session_unset();
$_SESSION = "";

echo "<script> alert(\"Logout erfolgreich. Bis bald!\"); window.location.href = \"index.html\"; </script>";
?>