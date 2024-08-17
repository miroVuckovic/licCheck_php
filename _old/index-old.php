    <?php

    include "scripts/php/query.php";

    readfile("html/head.html");
    readfile("html/login.html");

    echo "<body>";

    readfile("html/nav.html");

    $cadParam;

    header_remove();

    function initQuery()
    {

        if (isset($_GET['cad'])) {

            global $cadParam;
            $cadParam = $_GET['cad'];

            $url = $_SERVER['PHP_SELF'] . '?cad=' . $cadParam;

            header("Refresh: 5; url=$url");

            switch ($_GET['cad']) {
                case 'autocad':
                    populateLicenseTable("cad", "27000", "HR-SBD-LIC-01.cadenas.internal", "64400AMECH_PP_T_F");
                    break;
                case 'inventor':
                    populateLicenseTable("cad", "27000", "HR-SBD-LIC-01.cadenas.internal", "64801INVPROSA_T_F");
                    break;
                case 'solidworks':
                    populateLicenseTable("cad", "25734", "HR-SBD-LIC-01.cadenas.internal", "solidworks");
                    break;
                case 'nx':
                    populateLicenseTable("cad", "28000", "HR-SBD-LIC-01.cadenas.internal", "NXPTR101");
                    break;
                case 'creo':
                    populateLicenseTable("cad", "7788", "HR-SBD-LIC-01.cadenas.internal", "PROE_CreoPartnerDemo");
                    break;
                case 'revit':
                    populateLicenseTable("cad", "27000", "HR-SBD-LIC-01.cadenas.internal", "85950RVT_T_F");
                    break;
                case 'zuken':
                    populateLicenseTable("cad", "2837", "hr-sbd-lic-01.cadenas.internal", "E3panel");
                    break;
                case 'autocad-mnet':
                    populateLicenseTable("cad", "27000", "mnetlic01.cadenas.internal", "64400AMECH_PP_T_F");
                    break;
                case 'inventor-mnet':
                    populateLicenseTable("cad", "27000", "mnetlic01.cadenas.internal", "64801INVPROSA_T_F");
                    break;
                case 'revit-mnet':
                    populateLicenseTable("cad", "27000", "mnetlic01.cadenas.internal", "85950RVT_T_F");
                    break;
                case 'solidworks-mnet':
                    populateLicenseTable("cad", "25734", "mnetlic01.cadenas.internal", "solidworks");
                    break;
                default:
            }
        }
    }

    initQuery();



    // while (true) {
    //     initQUery(); // Call your function
    //     sleep(5);
    // }

    // function refreshPage($sec)
    // {
    //     global $cadParam;
    //     if (isset($_GET['cad'])) {
    //         $page = $_SERVER['PHP_SELF'] . '?cad=' . $cadParam;
    //         // header("Refresh: $sec; url=$page");
    //     }
    // }

    // refreshPage(5);

    echo "</body>";

    readfile("html/footer.html");

    ?>
