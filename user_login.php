<?php
session_start();
include ("dbconnect.php");

if(isset($_GET["login"])) {
    $name = $_POST["name"];
    $passwort = $_POST["passwort"];

    $sql = "SELECT * FROM user WHERE username = '$name'";
    $erg = mysqli_query ($con, $sql);
    $user = mysqli_fetch_array($erg);

    //Überprüfung des Passworts
    if ($user !== NULL) {
        if (password_verify($passwort, $user['passwort'])) {
        $_SESSION['userid'] = $user['userid'];
        $_SESSION['username'] = $user['username'];
        header("Location: startmenue.php");
        } else {
            echo "<script> alert('Das Passwort war ungültig.'); </script>";
        }
    } else {
        echo "<script> alert('Der Username war ungültig.'); </script>";
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
            <li><a href="index.html">Start</a></li>
            <li><a class="active" href="user_login.php">Login</a></li>
            <li><a href="user_register.php">Registrieren</a></li>
            <li><a href="info.html">Info</a></li>
        </ul>
    </nav>
</header>
<main>
    <section class="main-container">
        <article class="flexbox">
            <div class="container-100">
                <form action="?login=1" method="post">
                    Username:<br>
                    <input type="text" size="30" maxlength="250" name="name"><br><br>
                    Passwort:<br>
                    <input type="password" size="30" maxlength="250" name="passwort"><br><br>
                    <input type="submit" value="Abschicken">
                </form>
            </div>
        </article>
    </section>
</main>
</body>
</html>