<?php
include("security.php");
my_session_start();
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
                <h2>Karteikarten löschen</h2><br>
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

                    $kartenid = $_POST["kartenid"]; // Wert aus dem versteckten Feld abrufen

                    //Karte und Einträge in Hilfstabelle werden gelöscht
                    $sql = "DELETE FROM kartendetails WHERE kartennr = '$kartenid'";
                    $erg = mysqli_query($con, $sql);

                    $sql = "DELETE FROM lernkarten WHERE kartenid = '$kartenid'";
                    $erg = mysqli_query($con, $sql);

                    if ($erg) {
                        $fehlerText = "Karteikarte wurde erfolgreich gelöscht.";
                    } else {
                        $fehlerText = "Beim Löschen ist leider ein Fehler aufgetreten.";
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
                            <?php
                            if (isset($_GET["auswahlModul"])) {
                                $modulnr = $_POST["modulnr"];

                                // Kartenauswahl
                                if($modulnr == 0) {
                                    $sql = "SELECT * FROM lernkarten WHERE userErstellt = '$userid'";
                                } else {
                                    $sql = "SELECT * FROM lernkarten WHERE modulnr = '$modulnr' AND userErstellt = '$userid'";
                                }
                                $erg = mysqli_query($con, $sql);
                                $arr = mysqli_fetch_row($erg);
                                if ($arr !== NULL) {
                                    ?>
                                    <select name="kartennr">
                                        <?php
                                    echo "<option value=\"$arr[0]\">".$arr[1]."</option>";
                                    while ($arr = mysqli_fetch_row($erg)) {
                                        echo "<option value=\"$arr[0]\">".$arr[1]."</option>";
                                    }
                            ?>
                        </select><br><br>
                        <input type="submit" value="Karte auswählen">
                    </form><br>
                    <?php
                                } else echo "Bei dieser Auswahl gibt es leider keine Treffer.";
                            }
                    }
                }
                if ($zeigeFormular) {
                ?>
                <form action="?karte=1" method="post">
                    <input type="hidden" name="kartenid" value="<?php echo $kartenid; ?>">
                    Vorderseite / Frage:<br><br>
                    <?php
                    echo "<pre class=\"db-text\" >$kartentext[1]</pre><br><br>";
                    ?>
                    Rückseite / Antwort:<br><br>
                    <?php
                    echo "<pre class=\"db-text\">$kartentext[2]</pre><br><br>";
                    ?>
                    <input type="submit" value="Karte löschen">
                </form><br>
                <a href="k_loeschen.php">
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
