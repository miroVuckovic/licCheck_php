<?php

session_start();

    include "app/helpers/misc.php";

    readfile("app/views/head.html");

    include "app/views/account-header.php";

    include "app/views/sidebar.php";

    echo "<body>";

    header_remove();

    echo "<main>";

if (isset($_SESSION['logged_in'])) {

    if (isset($_GET['page'])) {
        
        $page = $_GET['page'];
        switch ($page) {
            case "training":
                readfile("app/views/training.html");
                break;
            case "user-admin":
                include "app/views/user-admin.php";
                break;
            case "cad-admin";
                include "app/views/cad-admin.php";
                break;
            case "cad-lics";
                include "app/views/cad-lics.php";
                break;
            default:

        }
    }

    echo "</main>";
    echo "</body>";

    readfile("app/views/footer.html");

} else {
    // echo 'session:' . $_SESSION['logged_in'];
    // $path = __DIR__ . '/app/views/login.html';
    // echo $path;
    // readfile($path);
    // die;
    include "app/views/training.html";
}
