<?php
include ("dbconnect.php");

// Meine Session-Start-Funktion unterstützt die Verwaltung von Zeitstempeln
function my_session_start() {
    session_start();
    // Zu alte Session-ID nicht zulassen
    if (!empty($_SESSION['deleted_time']) && $_SESSION['deleted_time'] < time() - 180) {
        session_destroy();
        session_start();
    }
}

// Meine Funktion zur Erneuerung der Session-ID
function my_session_regenerate_id() {
    // Bei einer aktiven Session wird session_create_id() aufgerufen,
    // um sicherzustellen, dass es keine Kollisionen gibt.
    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
    }
    // WARNUNG: Niemals vertrauliche Zeichenfolgen als Präfix verwenden!
    $newid = session_create_id('FI-Trainer-');
    // Zeitstempel für das Löschen setzen. Session-Daten dürfen aus
    // verschiedenen Gründen nicht sofort gelöscht werden.
    $_SESSION['deleted_time'] = time();
    // Beenden der Session.
    session_commit();
    // Sicherstellen, dass die benutzerdefinierte Session-ID akzeptiert wird
    // HINWEIS: Für den normalen Betrieb muss use_strict_mode aktiviert werden.
    ini_set('session.use_strict_mode', 0);
    // Neue benutzerdefinierte Session-ID festlegen
    session_id($newid);
    // Starten mit benutzerdefinierter Session-ID
    session_start();
}

// Standardfunktion, um Userinput zu bereinigen
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}