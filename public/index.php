<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();

if (isset($_SESSION['logged_in'])) {

    include "../app/helpers/query-cad-lics.php";

    readfile("../app/views/head.html");

    include "../app/views/account-header.php";

    include "../app/views/sidebar.php";

    echo "<body>";

    header_remove();

    echo "<main>";

    if (isset($_GET['page'])) {
        
        $page = $_GET['page'];
        switch ($page) {
            case "training":
                readfile("../app/views/training.html");
                break;
            case "user-admin":
                readfile("../app/views/user-admin.html");
                break;
            case "cad-admin";
                readfile("../app/views/cad-admin.html");
                break;
            case "cad-lics";
                include "../app/views/cad-lics.php";
                break;
            default:

        }
    }
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
