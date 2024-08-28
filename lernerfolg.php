<?php
session_start();
include ("user_handling.php");
include ("dbconnect.php");
?>

<!DOCTYPE html>
<html lang="device-width">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <title>FI-Trainer</title>
</head>
<body>
<header>
    <nav class="navigation">
        <input id="menu-toggle" type="checkbox" />
        <label class="menu-button-container" for="menu-toggle">
            <div class="menu-button"></div>
        </label>
        <ul class="menu">
            <li><a href="startmenue.php">Start</a></li>
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">Karteikarten</a>
                <div class="dropdown-content">
                    <a href="k_verwalten.php">verwalten</a>
                    <a href="k_lernen.php">lernen</a>
                    <a href="k_abfragen.php">abfragen</a>
                    <a href="k_uebersicht.php">Übersicht</a>
                </div>
            </li>
            <li><a href="hilfe.php">Hilfe</a></li>
            <li class="dropdown">
                <a class="active" href="javascript:void(0)" class="dropbtn"><img src="images/member-list_16069712.svg" alt="Benutzer" style="width:15px;height:15px;"></a>
                <div class="dropdown-content">
                    <a href="lernerfolg.php">Lernerfolg</a>
                    <a href="user_change_password.php">Passwort ändern</a>
                    <a href="user_logout.php">Logout</a>
                </div>
            </li>
        </ul>
    </nav>
</header>
<main>
    <section class="main-container">
        <article class="flexbox">
            <div class="container-100">
                <?php
                echo"<h1>Lernerfolg von $username</h1>";
                ?>
            </div>
        </article>
        <article class="flexbox">
            <div class="container-normal-100">
                <?php
                $sql = "SELECT COUNT(*) FROM lernkarten INNER JOIN kartendetails ON kartenid = kartennr WHERE usernr = '$userid'";
                $erg = mysqli_query($con, $sql);
                $arr = mysqli_fetch_row($erg);
                echo "<b>Lernkarten gesamt: $arr[0]</b>";
                ?>
            </div>
            <div class="container-normal-100">
                <?php
                for ($i = 0; $i < 5; $i++) {
                    $sql = "SELECT COUNT(*) FROM lernkarten INNER JOIN kartendetails ON kartenid = kartennr WHERE usernr = '$userid' AND lernstufe = '$i'";
                    $erg = mysqli_query($con, $sql);
                    $arr = mysqli_fetch_row($erg);
                    echo "<b>Lernstufe $i: " . $arr[0] . " Karten</b><br>";
                }
                ?>
            </div>
        </article>
        <?php
        $sql = "SELECT * FROM module";
        $erg = mysqli_query($con, $sql);
        while ($modul = mysqli_fetch_row($erg)) {
            $sql = "SELECT COUNT(*) FROM lernkarten INNER JOIN kartendetails ON kartenid = kartennr WHERE modulnr = '$modul[0]' AND usernr = '$userid'";
            $erg2 = mysqli_query($con, $sql);
            $arr = mysqli_fetch_row($erg2);
            if ($arr[0] !== "0") {
            ?>
            <article class="flexbox">
                <div class="container-normal-100">
                    <?php
                    echo "Lernkarten $modul[1]: $arr[0]";
                    ?>
                </div>
                <div class="container-normal-100">
                    <?php
                    for ($i = 0; $i < 5; $i++) {
                        $sql = "SELECT COUNT(*) FROM lernkarten INNER JOIN kartendetails ON kartenid = kartennr WHERE modulnr = '$modul[0]' AND usernr = '$userid' AND lernstufe = '$i'";
                        $erg2 = mysqli_query($con, $sql);
                        $arr = mysqli_fetch_row($erg2);
                        echo "Lernstufe $i: " . $arr[0] . " Karten<br>";
                    }

            ?>
                </div>
            </article>
            <?php
            }
        }
        ?>
    </section>
</main>
<footer></footer>
</body>
</html>