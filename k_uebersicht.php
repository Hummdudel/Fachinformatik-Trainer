<?php
session_start();
include ("user_handling.php");
include("dbconnect.php");
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
    <?php include("navigation.php") ?>
</header>
<main>
    <section class="main-container">
        <article class="flexbox">
            <div class="container-100">
                <?php
                echo"<h1>Karteikarten von $username – Übersicht</h1>";
                ?>
            </div>
        </article>
        <article class="flexbox">
            <div class="container-normal-100">
                <?php
                // Kartenausgabe
                if ($username == "admin") {
                    $sql = "SELECT username, frage, antwort, modulname, oeffentlich FROM lernkarten INNER JOIN kartendetails ON kartenid = kartennr INNER JOIN user ON usernr = userid INNER JOIN module ON modulnr = modulid WHERE userErstellt = userid ORDER BY username, modulname, frage";
                } else {
                    $sql = "SELECT frage, antwort, modulname, oeffentlich FROM lernkarten INNER JOIN module ON modulnr = modulid WHERE userErstellt = $userid ORDER BY modulname, frage";
                }
                $erg = mysqli_query($con, $sql);
                if ($username == "admin") {
                    ?>
                    <table><tr><th>User</th><th>Frage</th><th>Antwort</th><th>Modul</th><th>Öffentlich</th></tr><tr>
                    <?php
                } else {
                    ?>
                     <table><tr><th>Frage</th><th>Antwort</th><th>Modul</th><th>Öffentlich</th></tr><tr>
                     <?php
                }
                while ($arr = mysqli_fetch_row($erg)){
                    if ($username == "admin") {
                        if ($arr[4] == "1") {
                            $arr[4] = "ja";
                        }
                        else {
                            $arr[4] = "nein";
                        }
                    } else {
                        if ($arr[3] == "1") {
                            $arr[3] = "ja";
                        }
                        else {
                            $arr[3] = "nein";
                        }
                    }
                    foreach ($arr as $i) {
                        echo "<td><pre class=\"db-text\">" . $i . "</pre></td>";
                    }
                    echo "</tr><tr>";
                }
                ?>
                    </tr></table>
            </div>
        </article>
    </section>
</main>
<footer></footer>
</body>
</html>
