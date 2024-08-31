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
        function validateCheckboxes() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            let isChecked = false;

            // Überprüfen, ob mindestens eine Checkbox ausgewählt ist
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    isChecked = true;
                }
            });

            // Wenn keine Checkbox ausgewählt ist, wird eine Warnung ausgegeben und die Formulareinsendung verhindert
            if (!isChecked) {
                alert('Bitte wähle mindestens eine Karte aus.');
                return false;
            }

            return true;
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
                <h1>Karteikarten lernen</h1>
            </div>
        </article>
        <article class="flexbox">
            <div class="container-33">
                <h2>Karteikarten<br>auswählen</h2><br>
            </div>
            <div class="container-66">
                <?php
                if(isset($_GET["auswahlModul"])) {
                    $zeigeDetails = true;
                }

                if (isset($_GET["auswahlKarten"])) {
                    $zeigeAuswahl = false;
                    $zeigeFormular = true; // Variable ob das Kartenformular angezeigt werden soll

                    $kartenid = $_POST["kartennr"];

                    // Texte und Modulname aus Karte laden
                    foreach ($kartenid as $i) {
                        $sql = "SELECT * FROM lernkarten WHERE kartenid = '$i'";
                        $erg = mysqli_query($con, $sql);
                        $kartenauswahl[$i] = mysqli_fetch_row($erg);
                    }
                    foreach ($kartenauswahl as $i => $w) {
                        $sql = "SELECT modulname FROM module WHERE modulid = '$w[3]'";
                        $erg = mysqli_query($con, $sql);
                        $kartenauswahl[$i][4] = mysqli_fetch_row($erg)[0];
                    }

                    // Texte der Kartenauswahl in Session speichern
                    $_SESSION['originalKartenauswahlLernen'] = $_SESSION['kartenauswahlLernen'] = $kartenauswahl;

                    if (isset($_SESSION['kartenauswahlLernen'])) {
                        echo "<script> alert(\"Die Karten zum Lernen wurden erfolgreich ausgewählt.\"); window.location.href = \"k_lernen_action.php\"; </script>";
                    } else {
                        echo "<script> alert(\"Bei der Auswahl ist leider ein Fehler aufgetreten.\"); </script>";
                    }
                }

                if ($zeigeAuswahl) {
                    ?>
                    <form action="?auswahlModul=1" method="post">
                        <?php
                        if ($username !== "Gast") {
                        ?>
                        Umfang:<br>
                        <input type="radio" id="eig" name="umfang" value="eigene" checked>
                        <label for="eig">nur eigene Karten verwenden</label><br>
                        <input type="radio" id="oeff" name="umfang" value="oeffentliche">
                        <label for="oeff">auch öffentliche Karten anderer User verwenden</label><br><br>
                        Modul:<br>
                        <?php
                        } else {
                            ?>
                            <input type="hidden" name="umfang" value="oeffentliche">
                            <?php
                        }
                        ?>
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
                        <form action="?auswahlKarten=1" method="post" onsubmit="return validateCheckboxes()">
                            <?php
                            if (isset($_GET["auswahlModul"])) {
                                $umfang = $_POST["umfang"];
                                $modulnr = $_POST["modulnr"];

                                // Kartenauswahl
                                if ($umfang == "oeffentliche") {
                                    if ($modulnr == 0) {
                                        $sql = "SELECT DISTINCT kartenid, frage, antwort FROM lernkarten WHERE userErstellt = $userid OR oeffentlich = TRUE ORDER BY kartenid";
                                    } else {
                                        $sql = "SELECT DISTINCT kartenid, frage, antwort FROM lernkarten WHERE modulnr = $modulnr AND (userErstellt = $userid OR oeffentlich = TRUE) ORDER BY kartenid";
                                    }
                                } else {
                                    if ($modulnr == 0) {
                                        $sql = "SELECT DISTINCT kartenid, frage, antwort FROM lernkarten WHERE userErstellt = $userid ORDER BY kartenid";
                                    } else {
                                        $sql = "SELECT DISTINCT kartenid, frage, antwort FROM lernkarten WHERE modulnr = $modulnr AND userErstellt = $userid ORDER BY kartenid";
                                    }
                                }
                            }
                            $erg = mysqli_query($con, $sql);
                            $arr = mysqli_fetch_row($erg);
                            if ($arr !== NULL) {
                                ?>
                                <button type="button" onclick="toggleCheckboxes(true)">Alle auswählen</button>
                                <button type="button" onclick="toggleCheckboxes(false)">Alle abwählen</button><br><br>
                                <?php
                                echo "<label><input type=\"checkbox\" name=\"kartennr[]\" value=\"$arr[0]\" checked>" . $arr[1] . "</label><br>";
                                while ($arr = mysqli_fetch_row($erg)) {
                                    echo "<label><input type=\"checkbox\" name=\"kartennr[]\" value=\"$arr[0]\" checked>" . $arr[1] . "</label><br>";
                                }
                                ?>
                                <br>
                                <input type="submit" value="Mit diesen Karten lernen">
                                <?php
                            } else echo "Bei dieser Auswahl gibt es leider keine Treffer.";
                    } ?>
                        </form><br>
                <?php
                }
                ?>
            </div>
        </article>
    </section>
</main>
<footer></footer>
</body>
</html>
