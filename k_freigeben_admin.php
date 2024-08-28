<?php
session_start();
include ("user_handling.php");
include("dbconnect.php");

$kartenid = null; // Variable im globalen Bereich deklarieren

if($username !== "admin") {
    session_start();
    session_destroy();
    session_unset();
    $_SESSION = "";
    header("Location: index.html");
}

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
                <h2><a class="linkIntern" href="k_loeschen.php">Karteikarten<br>löschen</a></h2><br>
                <h2><a class="linkIntern" href="k_freigeben_admin.php">Karteikarten<br>freigeben</a></h2>
            </div>
            <div class="container-66">
                <h2>Karteikarten freigeben</h2><br>
                <?php
                if(isset($_GET["auswahlModul"])) {
                    $zeigeDetails = true;
                }

                if (isset($_GET["auswahlKarte"])) {
                    $zeigeAuswahl = false;
                    $zeigeFormular = true; // Variable ob das Kartenformular angezeigt werden soll

                    $kartenid = $_POST["kartennr"];

                    // Texte aus Karte laden
                    $sql = "SELECT * FROM lernkarten WHERE kartenid = '$kartenid'";
                    $erg = mysqli_query($con, $sql);
                    $kartentext = mysqli_fetch_row($erg);
                }

                if (isset($_GET["karte"])) {
                    $error = false;

                    $frage = $_POST["frage"];
                    $antwort = $_POST["antwort"];
                    $kartenid = $_POST["kartenid"]; // Wert aus dem versteckten Feld abrufen

                    //Überprüfe, dass es noch keine andere Karte mit dieser Frage gibt
                    $sql = "SELECT * FROM lernkarten WHERE frage = '$frage' AND kartenid != '$kartenid'";
                    $erg = mysqli_query($con, $sql);
                    $karte = mysqli_fetch_row($erg);

                    if ($karte !== NULL) {
                        $fehlerText = "Es gibt bereits eine andere Karte mit dieser Frage.";
                        $error = true;
                    }

                    //Keine Fehler, wir können die Karte freigeben und ggf. Änderungen speichern
                    if (!$error) {
                        $sql = "UPDATE lernkarten SET frage = '$frage', antwort = '$antwort', adminCheck = FALSE, oeffentlich = TRUE WHERE kartenid = '$kartenid'";
                        $erg = mysqli_query($con, $sql);

                        if ($erg) {
                            $fehlerText = "Karteikarte wurde erfolgreich freigegeben.";
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
                        <form action="?auswahlKarte=1" method="post">
                            Karte:<br>
                            <select name="kartennr">
                                <?php
                                if (isset($_GET["auswahlModul"])) {
                                    $modulnr = $_POST["modulnr"];

                                    // Kartenauswahl aller noch nicht freigegebenen Karten für Admin
                                    if($modulnr == 0) {
                                        $sql = "SELECT * FROM lernkarten WHERE adminCheck = TRUE";
                                    } else {
                                        $sql = "SELECT * FROM lernkarten WHERE modulnr = '$modulnr' AND adminCheck = TRUE";
                                    }
                                    $erg = mysqli_query($con, $sql);
                                    while ($arr = mysqli_fetch_row($erg)) {
                                        echo "<option value=\"$arr[0]\">".$arr[1]."</option>";
                                    }
                                }
                                ?>
                            </select><br><br>
                            <input type="submit" value="Karte prüfen">
                        </form><br>
                        <?php
                    }
                }
                if ($zeigeFormular) {
                ?>
                <form action="?karte=1" method="post">
                    <input type="hidden" name="kartenid" value="<?php echo $kartenid; ?>">
                    Vorderseite / Frage:<br>
                    <?php
                    echo "<textarea name=\"frage\" required rows=\"4\" cols=\"50\">$kartentext[1]</textarea><br><br>";
                    ?>
                    Rückseite / Antwort:<br><br>
                    <?php
                    echo "<textarea name=\"antwort\" required rows=\"4\" cols=\"50\">$kartentext[2]</textarea><br><br>";
                    ?>
                    <input type="submit" value="Karte freigeben"> <input type="reset" value="Text wiederherstellen">
                </form><br>
                <a href="k_freigeben_admin.php">
                    <input type="button" value="Abbrechen" />
                </a>
            </div>
        </article>
    </section>
</main>
<?php
} // Ende von if($zeigeFormular)
?>
<footer></footer>
</body>
</html>
