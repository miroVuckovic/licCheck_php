<?php

// session_start();



$demoMode = true;

echo "<header class='account-header'>";

if ($demoMode == true) {
    echo "<figure class='demo'>";
    echo "DEMO";
    echo "</figure>";
}

echo "<figure class='account-box'>";

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $role_desc = $_SESSION['role_desc'];
    // $role = $_SESSION['role'];
    echo "<div class='login'>";
    echo "Korisnik: ";
    echo htmlspecialchars($username);
    echo "<br>";
    echo "Rola: " . translateRoleDescriptions(htmlspecialchars($role_desc));
    echo "<br>";
    echo "<a class='login' href='app/models/logout.php'>Odjava</a>";
    echo "</div>";
} else {
    echo "<div class='login'>";
    echo "Korisnik nije ulogiran.";
    echo "<br>";
    echo "<a class='login' href='app/views/login.html'>Prijava</a>";
    echo "</div>";
    echo "<a class='login' href='app/views/register.html'>Registracija</a>";
    echo "</div>";
}



echo "</figure>";
echo "</header>";
