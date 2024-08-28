<?php
session_start();
include ("user_handling.php");
include("dbconnect.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Passwort ändern</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
<header>
    <?php include("navigation.php") ?>
</header>

<?php
$zeigeFormular = true; //Variable ob das Registrierungsformular angezeigt werden soll

if(isset($_GET["changePassword"])) {
    $error = false;
    $passwortAlt = $_POST["passwortAlt"];
    $passwortNeu = $_POST["passwortNeu"];
    $passwortNeu2 = $_POST["passwortNeu2"];

    $sql = "SELECT * FROM user WHERE username = '$username'";
    $erg = mysqli_query ($con, $sql);
    $user = mysqli_fetch_array($erg);

    //Überprüfung des alten Passworts
    if (!password_verify($passwortAlt, $user['passwort'])) {
        $fehlerText = "Das alte Passwort war ungültig";
        $error = true;
    }

    if ($passwortNeu != $passwortNeu2) {
        $fehlerText = "Die Passwörter müssen übereinstimmen";
        $error = true;
    }

    //Keine Fehler, wir können das Passwort ändern
    if (!$error) {
        $passwort_hash = password_hash($passwortNeu, PASSWORD_DEFAULT);

        $sql = "UPDATE user SET passwort = '$passwort_hash' WHERE username = '$username'";
        $erg = mysqli_query($con, $sql);

        if ($erg) {
            $loginText = "Das Passwort wurde erfolgreich geändert";
            session_destroy();
            session_unset();
            $_SESSION = "";
        } else {
            $fehlerText = "Beim Abspeichern ist leider ein Fehler aufgetreten";
        }
    }

    if(isset($fehlerText)) {
        echo "<script> alert(\"$fehlerText\"); </script>";
    } elseif (isset($loginText)) {
        echo "<script> alert(\"$loginText\"); window.location.href = \"user_login.php\"; </script>";
    }
}

if($zeigeFormular) {
?>

<main>
    <section class="main-container">
        <article class="flexbox">
            <div class="container-100">
                <h1>Passwort ändern</h1><br>
                <form action="?changePassword=1" method="post">
                    Altes Passwort:<br>
                    <input type="password" size="40" maxlength="250" name="passwortAlt" required><br><br><br>

                    Neues Passwort:<br>
                    <input type="password" size="40"  maxlength="250" name="passwortNeu" required><br><br>

                    Neues Passwort wiederholen:<br>
                    <input type="password" size="40" maxlength="250" name="passwortNeu2" required><br><br>

                    <input type="submit" value="Abschicken">
                </form>
            </div>
        </article>
    </section>
    <?php
    } //Ende von if($zeigeFormular)
    ?>
</main>
</body>
</html>