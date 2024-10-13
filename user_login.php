<?php
include("security.php");
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'strict');
ini_set('session.use_strict_mode', 1);
my_session_start();

include ("dbconnect.php");

if(isset($_GET["login"])) {
    $name = test_input($_POST["name"]);
    $passwort = test_input($_POST["passwort"]);

    $sql = $con->prepare("SELECT * FROM user WHERE username = ?");
    $sql->bind_param("s", $name);
    $sql->execute();
    $erg = $sql->get_result();
    $user = $erg->fetch_assoc();
    $sql->close();

    //Überprüfung des Passworts
    if ($user !== NULL) {
        if (password_verify($passwort, $user['passwort'])) {
            my_session_regenerate_id();
            $_SESSION['userid'] = $user['userid'];
            $_SESSION['username'] = $user['username'];
            header("Location: startmenue.php");
        } else {
            echo "<script> alert('Username oder Passwort ungültig.'); </script>";
        }
    } else {
        echo "<script> alert('Username oder Passwort ungültig.'); </script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
<header>
    <nav class="navigation">
        <input id="menu-toggle" type="checkbox" />
        <label class="menu-button-container" for="menu-toggle">
            <div class="menu-button"></div>
        </label>
        <ul class="menu">
            <li><a href="index.html">FI-Trainer</a></li>
            <li><a class="active" href="user_login.php">Anmelden</a></li>
            <li><a href="user_register.php">Registrieren</a></li>
            <li><a href="info.html">Info</a></li>
        </ul>
    </nav>
</header>
<main>
    <section class="main-container">
        <article class="flexbox">
            <div class="container-100">
                <h1>Anmelden</h1><br>
                <form action="?login=1" method="post">
                    Username:<br>
                    <input type="text" size="30" maxlength="250" name="name"><br><br>
                    Passwort:<br>
                    <input type="password" size="30" maxlength="250" name="passwort"><br><br>
                    <input type="submit" value="Anmelden">
                </form><br><br><br>
                <a style="color: white" href="guest_login.php">Als Gast anmelden</a>
            </div>
        </article>
    </section>
</main>
</body>
</html>