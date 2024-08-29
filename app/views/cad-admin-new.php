<?php

require("../config/database_pdo.php");

// Fetch CADs and data
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
        echo '<button type="submit" onclick="redirectToPage()" class="tablinks">' . $displayName . '</button>';
        echo '</form>';
    }
}

echo "</nav>";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cad']) && isset($_POST['cad-port']) && isset($_POST['cad-server']) && isset($_POST['cad-product'])) {
    $cadName = $_POST['cad'];
    $cadPort = $_POST['cad-port'];
    $cadServer = $_POST['cad-server'];
    $cadProduct = $_POST['cad-product'];

    initQuery($cadName, $cadPort, $cadServer, $cadProduct);
}

function initQuery($cad, $port, $server, $product)
{
    // Handle the query here
    // Replace `$_GET` with the necessary logic if needed
    // echo "Initiating query with CAD: $cad, Port: $port, Server: $server, Product: $product";
    // Implement the query or function you need here
    populateLicenseTable($cad, $port, $server, $product);
}

// Make sure to close HTML tags correctly
echo "<script src='app/helpers/page_loader.js'></script>";

?>

