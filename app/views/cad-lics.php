<?php

function initQuery()
{
    readfile("../app/views/nav.html");

    if (isset($_GET['cad'])) {       

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
                populateLicenseTable("Autocad", "27000", "server.example.com", "cad");
                break;
            case 'inventor':
                populateLicenseTable("Inventor", "27000", "server.example.com", "cad");
                break;
            case 'solidworks':
                populateLicenseTable("Solidworks", "25734", "server.example.com", "cad");
                break;
            case 'nx':
                populateLicenseTable("NX", "28000", "server.example.com", "cad");
                break;
            case 'creo':
                populateLicenseTable("Creo Parametric", "7788", "server.example.com", "cad");
                break;
            case 'revit':
                populateLicenseTable("Revit", "27000", "server.example.com", "cad");
                break;
            case 'all':
                populateLicenseTable("Revit", "27000", "server.example.com", "all");
                break;
            default:
        }
    }
}


initQuery();
