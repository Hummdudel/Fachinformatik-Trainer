<?php
include("security.php");
my_session_start();
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
                    <h1>Karteikarten verwalten</h1>
                </div>
            </article>
            <article class="flexbox">
                <div class="container-33">
                    <h2><a class="linkIntern" href="k_anlegen.php">Karteikarten<br>anlegen</a></h2><br>
                    <h2><a class="linkIntern" href="k_bearbeiten.php">Karteikarten<br>bearbeiten</a></h2><br>
                    <h2><a class="linkIntern" href="k_loeschen.php">Karteikarten<br>löschen</a></h2>
                    <?php if($userid !== 1){
                        echo "<br><h2><a class=\"linkIntern\" href=\"k_veroeffentlichen.php\">Karteikarten<br>veröffentlichen</a></h2>";
                    } else {
                        echo "<br><h2><a class=\"linkIntern\" href=\"k_freigeben_admin.php\">Karteikarten<br>freigeben</a></h2>";
                    }
                    ?>
                </div>
                <div class="container-66">
                </div>
            </article>
        </section>
    </main>
<footer></footer>
</body>
</html>