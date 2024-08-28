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

<?php
$zeigeFormular = true; //Variable ob das Kartenformular angezeigt werden soll

if(isset($_GET["karte"])) {
    $error = false;
    $frage = $_POST["frage"];
    $antwort = $_POST["antwort"];
    $modul = $_POST["modul"];

    //Überprüfe, dass es die Karte mit dieser Frage noch nicht gibt
    $sql = "SELECT * FROM lernkarten WHERE frage = '$frage' AND oeffentlich = TRUE";
    $erg = mysqli_query($con, $sql);
    $karte = mysqli_num_rows($erg);

    if ($karte !== 0) {
        $fehlerText = "Die Karte mit dieser Frage gibt es schon.";
        $error = true;
    }

    //Keine Fehler, wir können die Karte und die Verknüpfung in der Hilfstabelle speichern
    if (!$error) {
        $sql = "INSERT INTO lernkarten (frage, antwort, modulnr, userErstellt, oeffentlich) VALUES ('$frage', '$antwort', '$modul', '$userid', FALSE)";
        $erg = mysqli_query($con, $sql);

        // Abfrage von Karten-ID für den Eintrag in die Hilfstabelle (kartendetails)
        $sql = "SELECT kartenid FROM lernkarten WHERE frage = '$frage'";
        $arr = mysqli_query($con, $sql);
        $kartenid = mysqli_fetch_row($arr);

        $sql = "INSERT INTO kartendetails (kartennr, usernr, lernstufe) VALUES ('$kartenid[0]', '$userid', '0')";
        $erg = mysqli_query($con, $sql);

        if ($erg) {
            $fehlerText = "Karteikarte wurde erfolgreich angelegt.";
        } else {
            $fehlerText = "Beim Abspeichern ist leider ein Fehler aufgetreten";
        }
    }
}

if($zeigeFormular) {
    ?>

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
                    <h2>Karteikarten anlegen</h2><br>
                    <form action="?karte=1" method="post">
                        Vorderseite / Frage:</p><br>
                        <textarea name="frage" required rows="4" cols="45"></textarea><br><br>
                        Rückseite / Antwort:<br><br>
                        <textarea name="antwort" required rows="4" cols="45"></textarea><br><br>
                        Modul:<br>
                        <select name="modul">
                            <?php
                            $sql = "SELECT * FROM module";
                            $erg = mysqli_query ($con, $sql);
                            while ($arr = mysqli_fetch_row ($erg))
                                echo "<option value = \"$arr[0]\">".$arr[1]."</option>";
                            ?>
                        </select><br><br>
                        <input type="submit" value="Anlegen"> <input type="reset" value="Text löschen">
                    </form>
                    <?php
                    if(isset($fehlerText)) {
                        echo "<script> alert(\"$fehlerText\"); </script>";
                    }
                    ?>
                </div>
                </article>
        </section>
    </main>
    <?php
} //Ende von if($zeigeFormular)
?>
<footer></footer>
</body>
</html>