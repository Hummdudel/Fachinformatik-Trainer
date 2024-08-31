<?php
$name = $_SESSION['username'];
?>

<nav class="navigation">
    <input id="menu-toggle" type="checkbox" />
    <label class="menu-button-container" for="menu-toggle">
        <div class="menu-button"></div>
    </label>
    <ul class="menu">
        <li><a class="<?= basename($_SERVER['PHP_SELF']) == 'startmenue.php' ? 'active' : '' ?>" href="startmenue.php">Start</a></li>
        <li class="dropdown">
            <a class="<?= in_array(basename($_SERVER['PHP_SELF']), ['k_verwalten.php', 'k_anlegen.php', 'k_bearbeiten.php', 'k_freigeben_admin.php', 'k_loeschen.php', 'k_veroeffentlichen.php', 'k_lernen.php', 'k_lernen_action.php', 'k_abfragen.php', 'k_abfragen_action.php', 'k_uebersicht.php']) ? 'active' : '' ?>" href="javascript:void(0)" class="dropbtn">Karteikarten</a>
            <div class="dropdown-content">
                <?php
                if ($name == 'Gast') {
                ?>
                <a href="k_lernen.php">lernen</a>
                <?php
                } else {
                ?>
                <a href="k_verwalten.php">verwalten</a>
                <a href="k_lernen.php">lernen</a>
                <a href="k_abfragen.php">abfragen</a>
                <a href="k_uebersicht.php">Übersicht</a>
                <?php
                }
                ?>
            </div>
        </li>
        <li><a class="<?= basename($_SERVER['PHP_SELF']) == 'hilfe.php' ? 'active' : '' ?>" href="hilfe.php">Hilfe</a></li>
        <li class="dropdown">
            <a class="<?= in_array(basename($_SERVER['PHP_SELF']), ['lernerfolg.php', 'user_change_password.php']) ? 'active' : '' ?>" href="javascript:void(0)" class="dropbtn"><img src="<?= in_array(basename($_SERVER['PHP_SELF']), ['lernerfolg.php', 'user_change_password.php']) ? 'images/member-list_16069712.svg' : 'images/member-list-inverted02.png' ?>" alt="Benutzer" style="width:15px;height:15px;"></a>
            <div class="dropdown-content">
                <?php
                if ($name == 'Gast') {
                    ?>
                    <a href="user_logout.php">Logout</a>
                    <?php
                } else {
                ?>
                <a href="lernerfolg.php">Lernerfolg</a>
                <a href="user_change_password.php">Passwort ändern</a>
                <a href="user_logout.php">Logout</a>
                    <?php
                }
                ?>
            </div>
        </li>
    </ul>
</nav>