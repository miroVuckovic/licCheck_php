<?php

// session_start();

echo "<header class='account-header'>";
echo "<figure class='account-box'>";

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $role_desc = $_SESSION['role_desc'];
    // $role = $_SESSION['role'];
    echo "<div class='login'>";   
    echo "Korisnik: ";
    echo htmlspecialchars($username);
    echo "<br>";
    echo "Rola: " . htmlspecialchars($role_desc);
    echo "</div>";
} else {
    echo "<div class='login'>";   
    echo "Korisnik nije ulogiran.";
    echo "</div>";
}

echo "<a class='login' href='app/models/logout.php'>Log out</a>";

echo "</figure>";
echo "</header>";
