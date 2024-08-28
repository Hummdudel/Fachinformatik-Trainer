<?php
session_start();
include ("user_handling.php");
include("dbconnect.php");

$kartenid = null; // Variable im globalen Bereich deklarieren

$zeigeFormular = false;
$zeigeAuswahl = true;
$zeigeDetails = false;
?>

<!DOCTYPE html>
<html lang="device-width">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <script>
        function toggleCheckboxes(toggle) {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = toggle;
            });
        }
    </script>
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
                <?php if($userid !== "1"){
                    echo "<br><h2><a class=\"linkIntern\" href=\"k_veroeffentlichen.php\">Karteikarten<br>veröffentlichen</a></h2>";
                } else {
                    echo "<br><h2><a class=\"linkIntern\" href=\"k_freigeben_admin.php\">Karteikarten<br>freigeben</a></h2>";
                }
                ?>
            </div>
            <div class="container-66">
                <h2>Karteikarten veröffentlichen</h2><br>
                <?php
                if(isset($_GET["auswahlModul"])) {
                    $zeigeDetails = true;
                }

                if (isset($_GET["auswahlKarten"])) {
                    $zeigeAuswahl = false;
                    $zeigeFormular = true; // Variable ob das Kartenformular angezeigt werden soll

                    $kartenid = $_POST["kartennr"];

                    foreach ($kartenid as $i) {
                        $sql = "UPDATE lernkarten SET adminCheck = TRUE WHERE kartenid = '$i'";
                        $erg = mysqli_query($con, $sql);
                        if ($erg) {
                            $fehlerText = "Die Karteikarten wurden erfolgreich zur Veröffentlichung ausgewählt. Sobald sie vom Admin freigegeben wurden, sind sie öffentlich verwendbar.";
                        } else {
                            $fehlerText = "Beim Abspeichern ist leider ein Fehler aufgetreten.";
                        }
                    }

                    if (isset($fehlerText)) {
                        echo "<script> alert(\"$fehlerText\"); </script>";
                    }
                }

                if ($zeigeAuswahl) {
                    ?>
                    <form action="?auswahlModul=1" method="post">
                        Modul:<br>
                        <select name="modulnr">
                            <option value="0">alle Module</option>
                            <?php
                            $sql = "SELECT * FROM module";
                            $erg = mysqli_query($con, $sql);
                            while ($arr = mysqli_fetch_row($erg)) {
                                echo "<option value=\"$arr[0]\">".$arr[1]."</option>";
                            }
                            ?>
                        </select>
                        <input type="submit" value="Karten anzeigen"><br><br>
                    </form>
                    <?php
                    if ($zeigeDetails) {
                        ?>
                        <form action="?auswahlKarten=1" method="post">
                            <?php
                            if (isset($_GET["auswahlModul"])) {
                                $modulnr = $_POST["modulnr"];

                                // Kartenauswahl
                                if($modulnr == 0) {
                                    $sql = "SELECT DISTINCT kartenid, frage FROM lernkarten WHERE userErstellt = $userid AND oeffentlich = FALSE AND adminCheck = FALSE ORDER BY kartenid";
                                } else {
                                    $sql = "SELECT DISTINCT kartenid, frage FROM lernkarten WHERE modulnr = $modulnr AND userErstellt = $userid AND oeffentlich = FALSE AND adminCheck = FALSE ORDER BY kartenid";
                                }
                                $erg = mysqli_query($con, $sql);
                                $arr = mysqli_fetch_row($erg);
                                if ($arr !== NULL) {
                                    ?>
                                    <button type="button" onclick="toggleCheckboxes(true)">Alle auswählen</button>
                                    <button type="button" onclick="toggleCheckboxes(false)">Alle abwählen</button><br><br>
                                    <?php
                                    echo "<label><input type=\"checkbox\" name=\"kartennr[]\" value=\"$arr[0]\" required checked>" . $arr[1] . "</label><br>";
                                    while ($arr = mysqli_fetch_row($erg)) {
                                        echo "<label><input type=\"checkbox\" name=\"kartennr[]\" value=\"$arr[0]\" required checked>" . $arr[1] . "</label><br>";
                                    }
                                    ?>
                                    <br>
                                    <input type="submit" value="Diese Karten veröffentlichen">
                                    <?php
                                } else echo "Alle Lernkarten sind entweder schon veröffentlicht oder warten auf eine Freigabe vom Admin.";
                            } ?>
                        </form><br>
                        <?php
                    }
                }
                    ?>
            </div>
        </article>
    </section>
</main>
<footer></footer>
</body>
</html>
