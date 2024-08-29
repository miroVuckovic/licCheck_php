<?php

// include "misc.php";

function populateLicenseTable($cadSystem, $port, $server, $product, $displayName)
{

    // header("Refresh: 5; url='index.php?cad=$cadSystem'");

    if ($product=="all") {
        die;
    }

    $currentDir = getcwd();

    // echo $currentDir;

    $localPath = "$currentDir\\app\\helpers";
    $localFullPath = "$localPath\\cad\\Release\\net8.0\\lmutil_demo.exe";

    echo '<aside id="lic-summary">';

    echo "Informacije o upotrebi licenci za:";
    echo "<br>";
    echo "<br>";
    echo $displayName;
    echo "<br>";
    echo "<br>";

    if (!file_exists($localFullPath)) {
        echo "ERROR - Pozadinski servis za " . $displayName . " ne postoji!";
        echo "<br>";
    }

    $command = "$localFullPath lmstat -a -c $port@$server -f cad";

    exec($command, $output, $return_var);

    if ($return_var !== 0) {
        echo "<p>Greška pri inicijalizaciji komande.</p>";
        exit(1);
    }

    $productString = "Users of $product";

    // echo $productName;

    $filteredOutputLicNo = array_filter($output, function ($line) use ($productString) {
        return stripos($line, $productString) !== false;
    });

    if (empty($filteredOutputLicNo)) {

        echo "<p>Sve licence dostupne.</p>";
    } else {

        foreach ($filteredOutputLicNo as $line) {

            $words = preg_split('/\s+/', $line);

            $totalLics = htmlspecialchars($words[5]);
            $usedLics = htmlspecialchars($words[10]);

            $freeLics = $totalLics - $usedLics;          

            echo "Ukupni broj licenci: " . htmlspecialchars($words[5]);
            echo "<br>";
            echo "Broj iskorištenih licenci: " . htmlspecialchars($words[10]);
            echo "<br>";
            echo "Broj slobodnih licenci: " . $freeLics;
            echo "<br>";
            echo "<br>";

            echo "</aside>";
        }

        // Filtriranje outputa po riječi "start" (svaka linija koja nam treba sadrži riječ "start")
        $filteredOutput = array_filter($output, function ($line) {
            return stripos($line, 'start') !== false;
        });

        $date = date_create();

        if (empty($filteredOutput)) {

            echo "<p>No licenses taken.</p>";
        } else {

            echo "<div class='table-container'>";
            echo "<table>";
            echo "<thead><tr><th>No.</th><th>Korisnici</th><th>Računalo</th><th>Display</th><th>Početni datum</th></tr></thead>";
            // echo "<br>";
            echo "<tbody>";

            foreach ($filteredOutput as $line) {

                echo "<tr>";
                // Splitanje linije po razmacima.
                $words = preg_split('/\s+/', $line);

                $lineNo = array_search($line, $filteredOutput);

                // samo za debugging
                // echo count($words);

                // Output koji trebamo počinje na liniji 23, tako da:
                $lineNo = $lineNo - 23;

                echo "<td>" . $lineNo . "</td>";
                echo "<td>" . htmlspecialchars($words[0]) . "</td>";
                echo "<td>" . htmlspecialchars($words[1]) . "</td>";
                echo "<td>" . htmlspecialchars($words[2]) . "</td>";
                echo "<td>" . htmlspecialchars($words[7]) . " " . reformatDate(htmlspecialchars($words[8])) . "." . date_format($date, "Y") . " " . htmlspecialchars($words[9]) . "</td>";            

                echo "</tr>";
            }

            echo "</tbody></table>";
            echo "</div>";

            echo "<br>";

            clearstatcache();
        }
    }
}
