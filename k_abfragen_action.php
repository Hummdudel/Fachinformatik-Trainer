<?php
include("security.php");
my_session_start();
include ("user_handling.php");
include("dbconnect.php");

$kartenauswahl = $_SESSION['kartenauswahlAbfragen'];

// Initialisiere $lernerfolg aus der Session oder neu
if (isset($_SESSION['lernerfolg'])) {
    $lernerfolg = $_SESSION['lernerfolg'];
} else {
    $lernerfolg = [];
    foreach ($kartenauswahl as $i) {
        $lernerfolg[$i[0]] = lese_lernstufe($i[0], $userid);
    }
    $_SESSION['lernerfolg'] = $lernerfolg;
}

$currentQuestionIndex = isset($_GET['frage']) ? intval($_GET['frage']) : 0;
$zeigeAntwort = isset($_GET['zeigeAntwort']) && $_GET['zeigeAntwort'] == '1';

function lese_lernstufe($kartenid, $userid)
{
    include("dbconnect.php");
    $sql = "SELECT lernstufe FROM kartendetails WHERE kartennr = '$kartenid' AND usernr = '$userid'";
    $erg = mysqli_query($con, $sql);
    $arr = mysqli_fetch_row($erg);
    return $arr[0];
}

function aktualisiere_lernstufe($kartenid, $userid, $lernstufe)
{
    include("dbconnect.php");
    $sql = "UPDATE kartendetails SET lernstufe = '$lernstufe' WHERE kartennr = '$kartenid' AND usernr = '$userid'";
    mysqli_query($con, $sql);
}

if (isset($_POST["speichern"])) {
    foreach ($kartenauswahl as $karte) {
        $kartenid = $karte[0];
        aktualisiere_lernstufe($kartenid, $userid, $lernerfolg[$kartenid]);
    }
    echo "Lernerfolg wurde gespeichert.";
    echo "<script> alert(\"Lernerfolg wurde gespeichert.\"); </script>";
}
?>

<!DOCTYPE html>
<html lang="de">
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
                <h1>Karteikarten abfragen</h1>
            </div>
        </article>
        <article class="flexbox">
            <div class="container-33">
                <h2><a class="linkIntern" href="k_abfragen.php">Karten neu<br>auswählen</a></h2><br>
                <form action="k_abfragen_action.php?auswahlLernmodus=1" method="post">
                    <input type="radio" id="reihe" name="modus" value="geordnet" checked>
                    <label for="reihe">In Reihenfolge der Auswahl abfragen</label><br>
                    <input type="radio" id="zufall" name="modus" value="gemischt">
                    <label for="zufall">In zufälliger Reihenfolge abfragen</label><br><br>
                    <input type="submit" value="Start">
                </form>
            </div>
            <div class="container-66">
                <?php
                if (isset($_POST["modus"]) || isset($_GET["frage"])) {
                    $lernmodus = isset($_POST["modus"]) ? $_POST["modus"] : null;

                    if ($lernmodus == "gemischt" && !isset($_GET['frage'])) {
                        shuffle($kartenauswahl);
                        $_SESSION['kartenauswahlAbfragen'] = $kartenauswahl;
                    } else if ($lernmodus == "geordnet" && !isset($_GET['frage'])) {
                        $_SESSION['kartenauswahlAbfragen'] = $kartenauswahl = $_SESSION['originalKartenauswahlAbfragen'];
                    }

                    if (isset($_POST["status"]) && isset($_GET["frage"])) {
                        $status = $_POST["status"];
                        $currentKey = array_keys($kartenauswahl)[$currentQuestionIndex - 1];
                        $kartenid = $kartenauswahl[$currentKey][0];

                        if ($status == "richtig" && $lernerfolg[$kartenid] < 4) {
                            $lernerfolg[$kartenid] += 1;
                        } elseif ($status == "falsch" && $lernerfolg[$kartenid] > 0) {
                            $lernerfolg[$kartenid] -= 1;
                        }

                        // Speichere das aktualisierte $lernerfolg-Array in der Session
                        $_SESSION['lernerfolg'] = $lernerfolg;
                    }

                    if ($currentQuestionIndex < count($kartenauswahl)) {
                        $keys = array_keys($kartenauswahl);
                        $currentKey = $keys[$currentQuestionIndex];
                        $modul = $kartenauswahl[$currentKey][4];
                        $frage = $kartenauswahl[$currentKey][1];
                        $antwort = $kartenauswahl[$currentKey][2];

                        if (!$zeigeAntwort) {
                            echo "Modul: $modul<br><br>";
                            ?>
                            <form action="k_abfragen_action.php?frage=<?php echo $currentQuestionIndex; ?>&zeigeAntwort=1" method="post">
                                Frage:<br><br>
                                <pre class="db-text"><?php echo $frage; ?></pre><br><br>
                                <input type="submit" value="Antwort anzeigen"><br><br>
                            </form>
                            <?php
                        } else {
                            echo "Modul: $modul<br><br>";
                            ?>
                            <form action="k_abfragen_action.php?frage=<?php echo $currentQuestionIndex + 1; ?>" method="post">
                                Frage:<br><br>
                                <pre class="db-text"><?php echo $frage; ?></pre><br><br>
                                Antwort:<br><br>
                                <pre class="db-text"><?php echo $antwort; ?></pre><br><br>
                                <button type="submit" name="status" value="richtig">Richtig gewusst</button>
                                <button type="submit" name="status" value="falsch">Nicht (richtig) gewusst</button>
                            </form><br>
                            <?php
                        }
                    } else {
                        echo "Keine weiteren Fragen.";
                        ?>
                        <form action="k_abfragen_action.php" method="post">
                            <input type="hidden" name="speichern" value="1">
                            <button type="submit">Lernerfolg speichern</button>
                        </form>
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
