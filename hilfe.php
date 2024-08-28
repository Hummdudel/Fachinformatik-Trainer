<?php
session_start();
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
                <h1>FI-Trainer – Hilfe</h1>
            </div>
        </article>
        <article class="flexbox">
            <div class="container-normal-100">
                <h2>Karteikarten verwalten</h2>
            </div>
        </article>
        <article class="flexbox">
            <div class="container-33">
                <h3>Karteikarten anlegen</h3>
            </div>
            <div class="container-66">
                <p class="fliesstext">
                    Hier kannst du deine eigenen Lernkarten erstellen, indem du für jede eine Frage und Antwort
                    eingibst und anschließend das Modul auswählst, zu dem die Karte thematisch gehört.
                </p>
            </div>
        </article>
        <article class="flexbox">
            <div class="container-33">
                <h3>Karteikarten bearbeiten</h3>
            </div>
            <div class="container-66">
                <p class="fliesstext">
                    Falls du den Text deiner Karte noch einmal ändern möchtest, kannst du dies an dieser Stelle tun.<br><br>
                    Solltest du die zu ändernde Karte bereits veröffentlicht haben, muss sie bei Änderung zunächst
                    erneut von einem Admin freigegeben werden, bevor sie erneut öffentlich einsehbar ist.
                </p>
            </div>
        </article>
        <article class="flexbox">
            <div class="container-33">
                <h3>Karteikarten löschen</h3>
            </div>
            <div class="container-66">
                <p class="fliesstext">
                    Erklärt sich von selbst.
                </p>
            </div>
        </article>
        <article class="flexbox">
            <div class="container-33">
                <h3>Karteikarten veröffentlichen</h3>
            </div>
            <div class="container-66">
                <p class="fliesstext">
                    Falls du deine erstellten Karten anderen Usern zum Lernen zur Verfügung stellen möchtest, kannst du
                    dies an dieser Stelle tun.<br><br>
                    Bevor deine Karten öffentlich einsehbar sind, müssen sie noch von einem Admin freigegeben werden.
                </p>
            </div>
        </article>
        <article class="flexbox">
            <div class="container-33">
                <h2>Karteikarten lernen</h2>
            </div>
            <div class="container-66">
                <p class="fliesstext">
                    In diesem Übungsmodus trainierst du die Inhalte der Lernkarten.
                    Über den Radio-Button entscheidest du, ob du nur mit deinen eigenen Karten oder zusätzlich mit
                    öffentlichen Karten der anderen User lernen möchtest.<br><br>
                    Nach einer Modulauswahl (ein bestimmtes Modul oder alle Module) werden dir entsprechend der o.g.
                    Vorauswahl die entsprechenden Karten zur Lernauswahl angezeigt.<br><br>
                    Anschließend hast du noch die Möglichkeit einzustellen, ob die Fragen in der ursprünglichen oder
                    in zufälliger Reihenfolge angezeigt werden.
                </p>
            </div>
        </article>
        <article class="flexbox">
            <div class="container-33">
                <h2>Karteikarten abfragen</h2>
            </div>
            <div class="container-66">
                <p class="fliesstext">
                    Dieser Prüfungsmodus funktioniert im Prinzip wie der o.g. Übungsmodus (Karteikarten lernen),
                    mit der Erweiterung, dass jede Karte einer Lernstufe zugewiesen wird.<br><br>
                    Lässt du dich von einer Karte das erste Mal abfragen, hat diese Karte automatisch die Lernstufe 0.<br>
                    Bei jeder richtig gewussten Antwort erhöht sich die Lernstufe – bis auf maximal 4.<br>
                    Bei jeder nicht oder nicht korrekt gewussten Antwort verringert sich die Lernstufe wieder – bis auf
                    minimal 0.<br><br>
                    Im Prüfungsmodus kannst du deshalb eine Vorauswahl treffen, ob du Karten einer bestimmten Lernstufe
                    oder aller Lernstufen verwenden möchtest.<br><br>
                    Nachdem alle ausgewählten Karten durchlaufen wurden, hast du die Möglichkeit, deinen Lernerfolg zu
                    speichern.
                </p>
            </div>
        </article>
        <article class="flexbox">
            <div class="container-33">
                <h2>Karteikarten – Übersicht</h2>
            </div>
            <div class="container-66">
                <p class="fliesstext">
                    Hier kannst du dir eine Übersicht aller deiner eigenen Lernkarten anzeigen lassen.
                </p>
            </div>
        </article>
        <article class="flexbox">
            <div class="container-33">
                <h2>Lernerfolg</h2>
            </div>
            <div class="container-66">
                <p class="fliesstext">
                    Über diesen Unterpunkt im User-Menü wird dir dein bisheriger Lernerfolg in Karten pro
                    Lernstufe angezeigt.
                </p>
            </div>
        </article>
    </section>
</main>
<footer></footer>
</body>
</html>