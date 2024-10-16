<?php
include ("security.php");
include("dbconnect.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrieren</title>
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
            <li><a href="user_login.php">Anmelden</a></li>
            <li><a class="active" href="user_register.php">Registrieren</a></li>
            <li><a href="info.html">Info</a></li>
        </ul>
    </nav>
</header>

<?php
if(isset($_GET["register"])) {
    $error = false;
    $email = test_input($_POST["email"]);
    $name = test_input($_POST["name"]);
    $passwort = test_input($_POST["passwort"]);
    $passwort2 = test_input($_POST["passwort2"]);

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $fehlerText = "Bitte eine gültige E-Mail-Adresse eingeben";
        $error = true;
    }
    if (!preg_match("/^[0-9a-zA-Z1 ]*$/",$name)) {
        $fehlerText = "Bitte nur Buchstaben, Zahlen oder Leerzeichen im Benutzernamen";
        $error = true;
    }
    if(strlen($passwort) == 0) {
        $fehlerText = "Bitte ein Passwort eingeben";
        $error = true;
    }
    if($passwort != $passwort2) {
        $fehlerText = "Die Passwörter müssen übereinstimmen";
        $error = true;
    }

    //Überprüfe, dass die E-Mail-Adresse noch nicht registriert wurde
    if(!$error) {
        $sql = $con->prepare("SELECT * FROM user WHERE mail = ?");
        $sql->bind_param("s", $email);
        $sql->execute();
        $erg = $sql->get_result();
        $user = $erg->fetch_assoc();
        $sql->close();

        if($user !== NULL) {
            $fehlerText = "Diese E-Mail-Adresse ist bereits vergeben";
            $error = true;
        }
    }

    //Überprüfe, dass der Username noch nicht registriert wurde
    if(!$error) {
        $sql = $con->prepare("SELECT * FROM user WHERE username = ?");
        $sql->bind_param("s", $name);
        $sql->execute();
        $erg = $sql->get_result();
        $user = $erg->fetch_assoc();
        $sql->close();

        if($user !== NULL) {
            $fehlerText = "Dieser Username ist bereits vergeben";
            $error = true;
        }
    }

    //Keine Fehler, wir können den Nutzer registrieren
    if(!$error) {
        $passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);

        $sql = $con->prepare("INSERT INTO user (mail, username, passwort) VALUES (?, ?, ?)");
        $sql->bind_param("sss", $email, $name, $passwort_hash);
        $erg = $sql->execute();
        $sql->close();

        if($erg) {
            echo "<script> alert(\"Registrierung erfolgreich.\\nWeiter zum Login.\"); window.location.href = \"user_login.php\"; </script>";
        } else {
            $fehlerText = "Beim Abspeichern ist leider ein Fehler aufgetreten";
        }
    }
}
?>

<main>
    <section class="main-container">
        <article class="flexbox">
            <div class="container-100">
                <h1>Registrieren</h1><br>
                <form action="?register=1" method="post">
                    E-Mail:<br>
                    <input type="email" size="30" maxlength="250" name="email"><br><br>

                    Username:<br>
                    <input type="text" size="30" maxlength="250" name="name"><br><br>

                    Passwort:<br>
                    <input type="password" size="30"  maxlength="250" name="passwort"><br><br>

                    Passwort wiederholen:<br>
                    <input type="password" size="30" maxlength="250" name="passwort2"><br><br>

                    <input type="submit" value="Registrieren">
                </form>
            </div>
        </article>
    </section>
    <section class="main-container">
        <?php
        if(isset($fehlerText)) {
            echo "<script> alert('$fehlerText'); </script>";
        }
        ?>
</main>
</body>
</html>