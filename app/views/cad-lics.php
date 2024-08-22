<?php

function initQuery()
{

if (isset($_GET['cad'])) {

        readfile("../app/views/nav.html");

        global $cadParam;
        $cadParam = $_GET['cad'];

        if ($cadParam == 'all') {
            echo "</main>";
            echo "</body>";

            readfile("../app/views/footer.html");
            exit;
        }

            // $url = $_SERVER['PHP_SELF'] . '?cad=' . $cadParam;

            // header("Refresh: 5; url=$url");

            switch ($cadParam) {
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
}

initQuery();
