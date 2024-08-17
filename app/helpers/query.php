<?php

include "misc.php";

function populateLicenseTable($cadSystem, $port, $server, $product)
{

    // header("Refresh: 5; url='index-tabs.php?cad=$cadSystem'");

    $localPath = "c:\\xampp\\htdocs\\licCheck\\lmutils";
    $localFullPath = "$localPath\\cad\\Release\\net8.0\\lmutil_demo.exe";

    echo '<aside id="lic-summary">';

    echo "License usage information for :";
    echo "<br>";
    echo $cadSystem;
    echo "<br>";
    echo "<br>";

    if (!file_exists($localFullPath)) {
        echo "ERROR - Background service for " . $cadSystem . " doesn't exist!";
        echo "<br>";
    }

    // Define the command to execute
    $command = "$localFullPath lmstat -a -c $port@$server -f $product";

    // Execute the command and capture the output
    exec($command, $output, $return_var);

    // Check if the command executed successfully
    if ($return_var !== 0) {
        echo "<p>There was an error executing the command.</p>";
        exit(1);
    }

    $productString = "Users of $product";

    // echo $productName;

    // Filter the output for lines containing the keyword "$product"
    $filteredOutputLicNo = array_filter($output, function ($line) use ($productString) {
        return stripos($line, $productString) !== false;
    });

    if (empty($filteredOutputLicNo)) {

        echo "<p>All licenses available</p>";
    } else {

        foreach ($filteredOutputLicNo as $line) {

            // Split the line by spaces
            $words = preg_split('/\s+/', $line);

            $totalLics = htmlspecialchars($words[5]);
            $usedLics = htmlspecialchars($words[10]);

            $freeLics = $totalLics - $usedLics;

            

            echo "Total number of licenses: " . htmlspecialchars($words[5]);
            echo "<br>";
            echo "Used number of licenses: " . htmlspecialchars($words[10]);
            echo "<br>";
            echo "Free number of licenses: " . $freeLics;
            echo "<br>";
            echo "<br>";

            echo "</aside>";
        }

        // Filter the output for lines containing the keyword "start"
        $filteredOutput = array_filter($output, function ($line) {
            return stripos($line, 'start') !== false;
        });

        $date = date_create();

        // Check if there are any filtered lines to display
        if (empty($filteredOutput)) {

            echo "<p>No licenses taken.</p>";
        } else {

            echo "<table>";
            echo "<thead><tr><th>No.</th><th>Users</th><th>Computer</th><th>Display</th><th>Start date</th></tr></thead>";
            // echo "<br>";
            echo "<tbody>";

            foreach ($filteredOutput as $line) {

                echo "<tr>";
                // Split the line by spaces
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

            echo "<br>";

            clearstatcache();
        }
    }
}
