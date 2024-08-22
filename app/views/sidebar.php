<?php

$role_id = $_SESSION['role_id'];

$cad_lics = '<button class="sidebar-links" onclick="window.location.href=\'index.php?page=cad-lics\'">CAD licence</button>';
$cad_admin = '<button class="sidebar-links" onclick="window.location.href=\'index.php?page=cad-admin\'">CAD administracija</button>';
$user_admin = '<button class="sidebar-links" onclick="window.location.href=\'index.php?page=user-admin\'">Administracija korisnika</button>';
$user_training = '<button class="sidebar-links" onclick="window.location.href=\'index.php?page=training\'">Obuka za korisnike</button>';

echo "<a id=logo-sidebar href='index.php'>  <img src='public/images/clc_logo.png' alt='CLC logo'></a> ";

echo "<aside class='sidebar'>";

switch ($role_id) {

    case 2:
        echo $cad_lics;
        echo $user_training;
        break;
    case 3:
        echo $cad_lics;
        echo $user_training;
        break;
    default:
        echo $cad_lics;
        echo $cad_admin;
        echo $user_admin;
        echo $user_training;
        break;
}

echo "</aside>";