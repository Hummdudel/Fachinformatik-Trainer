<?php
// Überprüfen, ob der Benutzer angemeldet ist
if (!isset($_SESSION['userid'])) {
    // Falls nicht angemeldet, weiterleiten zur Login-Seite
    header('Location: user_login.php');
    exit();
}

$userid = $_SESSION['userid'];
$username = $_SESSION['username'];

// Falls als Gast angemeldet, bei nicht autorisierten Seiten weiter zum Startmenü
if($username == "Gast" && !in_array(basename($_SERVER['PHP_SELF']), ['startmenue.php', 'k_lernen.php', 'k_lernen_action.php', 'hilfe.php', 'user_logout.php'])) {
    header("Location: startmenue.php");
}
?>