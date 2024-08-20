<?php

$role_id = $_SESSION['role_id'];

$cad_lics = '<button class="sidebar-links" onclick="window.location.href=\'index.php?cad=all\'">CAD licence</button>';
$cad_admin = '<button class="sidebar-links" onclick="window.location.href=\'index.php\'">CAD administracija</button>';
$user_admin = '<button class="sidebar-links" onclick="window.location.href=\'index.php\'">Administracija korisnika</button>';

echo "<a id=logo-sidebar href='index.php'>  <img src='public/images/clc_logo.png' alt='CLC logo'></a> ";

echo "<aside class='sidebar'>";

if ($role_id == 1) {

    echo $cad_lics;
    echo $cad_admin;
    echo $user_admin;

} else {
    echo $cad_lics;
}

echo "</aside>";