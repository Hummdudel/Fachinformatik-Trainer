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
    <?php include("navigation.php") ?>
</header>
<main>
    <section class="main-container">
        <article class="flexbox">
            <div class="container-100">
                <?php
                echo"<h1>Willkommen, $username!</h1>";
                ?>
            </div>
        </article>
        <article class="flexbox">
            <?php
            if ($name !== "Gast") {
            ?>
                <div class="container-33">
                    <h2><a class="linkIntern" href="k_verwalten.php">Karteikarten<br>verwalten</a></h2>
                </div>
                <div class="container-33">
                    <h2><a class="linkIntern" href="k_lernen.php">Karteikarten<br>lernen</a></h2>
                </div>
                <div class="container-33">
                    <h2><a class="linkIntern" href="k_abfragen.php">Karteikarten<br>abfragen</a></h2>
                </div>
            <?php
            } else {
                ?>
                <div class="container-33">
                    <h2><a class="linkIntern" href="k_lernen.php">Karteikarten<br>lernen</a></h2>
                </div>
                <?php
            }
            ?>
        </article>
    </section>
</main>
<footer></footer>
</body>
</html>