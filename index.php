<?php

session_start();

if (!isset($_SESSION['loggedin'])) {
    readfile("html/login.html");
    die;
}

include "scripts/php/query.php";

readfile("html/head.html");

readfile("html/sidebar.html");

echo "<body>";

readfile("html/nav.html");

$cadParam;

header_remove();

echo "<main>";

function initQuery()
{

    if (isset($_GET['cad'])) {

        global $cadParam;
        $cadParam = $_GET['cad'];

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
            default:
        }
    }
}

initQuery();

echo "</main>";
echo "</body>";

echo "<script>
function openCAD(evt, cadName) {
  // Declare all variables
  var i, tabcontent, tablinks;

  // Get all elements with class='tabcontent' and hide them
  tabcontent = document.getElementsByClassName('tabcontent');
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = 'none';
  }

  // Get all elements with class='tablinks' and remove the class 'active'
  tablinks = document.getElementsByClassName('tablinks');
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(' active', '');
  }

  // Show the current tab, and add an 'active' class to the button that opened the tab
  document.getElementById(cadName).style.display = 'block';
  evt.currentTarget.className += ' active';
} 
</script>";

readfile("html/footer.html");

?>