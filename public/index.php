<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();

if (isset($_SESSION['logged_in'])) {

    // echo 'session:' . $_SESSION['logged_in'];

    include "../app/helpers/query-cad-lics.php";

    readfile("../app/views/head.html");

    include "../app/views/account-header.php";

    include "../app/views/sidebar.php";

    echo "<body>";

    $cadParam;

    header_remove();

    echo "<main>";

    function initQuery()
    {

        if (isset($_GET['cad'])) {

            readfile("../app/views/nav.html");

            global $cadParam;
            $cadParam = $_GET['cad'];

            if ($cadParam=='all'){
                echo "</main>";
                echo "</body>";
            
                readfile("../app/views/footer.html");   
                exit;
            }

            // $url = $_SERVER['PHP_SELF'] . '?cad=' . $cadParam;

            // header("Refresh: 5; url=$url");

            switch ($_GET['cad']) {
                case 'autocad':
                    // echo "<div id=" . "autocad"  . "class=tabcontent>";
                    populateLicenseTable("Autocad", "27000", "HR-SBD-LIC-01.cadenas.internal", "cad");
                    // echo "</div>";
                    break;
                case 'inventor':
                    // echo "<div id=" . "inventor"  . "class=tabcontent>";
                    populateLicenseTable("Inventor", "27000", "HR-SBD-LIC-01.cadenas.internal", "cad");
                    // echo "</div>";
                    break;
                case 'solidworks':
                    // echo "<div id=" . "solidworks"  . "class=tabcontent>";
                    populateLicenseTable("Solidworks", "25734", "HR-SBD-LIC-01.cadenas.internal", "cad");
                    // echo "</div>";
                    break;
                case 'nx':
                    populateLicenseTable("NX", "28000", "HR-SBD-LIC-01.cadenas.internal", "cad");
                    break;
                case 'creo':
                    populateLicenseTable("Creo Parametric", "7788", "HR-SBD-LIC-01.cadenas.internal", "cad");
                    break;
                case 'revit':
                    populateLicenseTable("Revit", "27000", "HR-SBD-LIC-01.cadenas.internal", "cad");
                    break;
                case 'all':
                    populateLicenseTable("Revit", "27000", "HR-SBD-LIC-01.cadenas.internal", "all");
                    break;
                default:
            }
        }
    }

    initQuery();

    echo "</main>";
    echo "</body>";

    readfile("../app/views/footer.html");   

} else {
    // echo 'session:' . $_SESSION['logged_in'];
    $path = __DIR__ . '/../app/views/login.html';
    // echo $path;
    readfile($path);
    die;
}
