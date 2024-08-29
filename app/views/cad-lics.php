<?php

require("config/database_pdo.php");

require("app/helpers/query-cad-lics.php");

$cadQuery = $pdo->query("SELECT id, cad_name, display_name, port, server, product, active FROM cad_systems");
$cads = $cadQuery->fetchAll(PDO::FETCH_ASSOC);

echo "<nav class='cad-nav-tab'>";

foreach ($cads as $cad) {
    if ($cad['active']) {
        $cadName = htmlspecialchars($cad['cad_name']);
        $displayName = htmlspecialchars($cad['display_name']);
        $port = htmlspecialchars($cad['port']);
        $server = htmlspecialchars($cad['server']);
        $product = htmlspecialchars($cad['product']);

        echo '<form class="cad-nav-button-form" method="post" action="">';
        echo '<input type="hidden" name="cad" value="' . $cadName . '">';
        echo '<input type="hidden" name="cad-port" value="' . $port . '">';
        echo '<input type="hidden" name="cad-server" value="' . $server . '">';
        echo '<input type="hidden" name="cad-product" value="' . $product . '">';
        echo '<input type="hidden" name="cad-display-name" value="' . $displayName . '">';
        echo '<button type="submit" onclick="redirectToPage()" class="tablinks">' . $displayName . '</button>';
        echo '</form>';
    }
}

echo "</nav>";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cad']) && isset($_POST['cad-port']) && isset($_POST['cad-server']) && isset($_POST['cad-product']) && isset($_POST['cad-display-name'])) {
    $cadName = $_POST['cad'];
    $cadPort = $_POST['cad-port'];
    $cadServer = $_POST['cad-server'];
    $cadProduct = $_POST['cad-product'];
    $cadDisplayName = $_POST['cad-display-name'];

    initQuery($cadName, $cadPort, $cadServer, $cadProduct, $cadDisplayName);
}

function initQuery($cad, $port, $server, $product, $displayName)
{
    populateLicenseTable($cad, $port, $server, $product,$displayName);
}

echo "<script src='app/helpers/page_loader.js'></script>";


