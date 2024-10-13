<?php
include("security.php");
my_session_start();
include ("user_handling.php");
include("dbconnect.php");

$kartenauswahl = $_SESSION['kartenauswahlLernen'];

$currentQuestionIndex = isset($_GET['frage']) ? intval($_GET['frage']) : 0;
$zeigeAntwort = isset($_GET['zeigeAntwort']) && $_GET['zeigeAntwort'] == '1';

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
                <h1>Karteikarten lernen</h1>
            </div>
        </article>
        <article class="flexbox">
            <div class="container-33">
                <h2><a class="linkIntern" href="k_lernen.php">Karten neu<br>auswählen</a></h2><br>
                <form action="k_lernen_action.php?auswahlLernmodus=1" method="post">
                    <input type="radio" id="reihe" name="modus" value="geordnet" checked>
                    <label for="reihe">In Reihenfolge der Auswahl lernen</label><br>
                    <input type="radio" id="zufall" name="modus" value="gemischt">
                    <label for="zufall">In zufälliger Reihenfolge lernen</label><br><br>
                    <input type="submit" value="Start">
                </form>
            </div>
            <div class="container-66">
                <?php
                if (isset($_POST["modus"]) || isset($_GET["frage"])) {
                    $lernmodus = isset($_POST["modus"]) ? $_POST["modus"] : null;

                    if ($lernmodus == "gemischt" && !isset($_GET['frage'])) {
                        shuffle($kartenauswahl);
                        $_SESSION['kartenauswahlLernen'] = $kartenauswahl;
                    } else if ($lernmodus == "geordnet" && !isset($_GET['frage'])) {
                        $_SESSION['kartenauswahlLernen'] = $kartenauswahl = $_SESSION ['originalKartenauswahlLernen'];
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
                            <form action="k_lernen_action.php?frage=<?php echo $currentQuestionIndex; ?>&zeigeAntwort=1" method="post">
                                Frage:<br><br>
                                <pre class="db-text"><?php echo $frage; ?></pre><br><br>
                                <input type="submit" value="Antwort anzeigen"><br><br>
                            </form>
                            <?php
                        } else {
                            echo "Modul: $modul<br><br>";
                            ?>
                            <form action="k_lernen_action.php?frage=<?php echo $currentQuestionIndex + 1; ?>" method="post">
                                Frage:<br><br>
                                <pre class="db-text"><?php echo $frage; ?></pre><br><br>
                                Antwort:<br><br>
                                <pre class="db-text"><?php echo $antwort; ?></pre><br><br>
                                <input type="submit" value="Nächste Frage">
                            </form><br>
                            <?php
                        }
                    } else {
                        echo "Keine weiteren Fragen.";
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
