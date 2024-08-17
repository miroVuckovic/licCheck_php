<?php

include "scripts/php/misc.php";

function populateLicenseTable($cadSystem, $port, $server, $product)
{

    header("Refresh: 5; url='index.php?cad=$cadSystem'");

    $localPath = "c:\\xampp\\htdocs\\licCheck\\lmutils";
    $localFullPath = "$localPath\\$cadSystem\\Release\\net8.0\\lmutil_demo.exe";

    echo "License usage information for " . ucfirst($cadSystem) . ":" ;
    echo "<br>";
    echo "<br>";

    if (!file_exists($localFullPath)) {
        echo "ERROR - Background service for " . ucfirst($cadSystem) . " doesn't exist!";
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
            echo "<tbody>";

            foreach ($filteredOutput as $line) {

                echo "<tr>";
                // Split the line by spaces
                $words = preg_split('/\s+/', $line);

                $lineNo = array_search($line, $filteredOutput);
                
                // The output we need starts at line 20, hence:
                $lineNo = $lineNo - 19;

                echo "<td>" . $lineNo . "</td>";
                echo "<td>" . htmlspecialchars($words[1]) . "</td>";
                echo "<td>" . htmlspecialchars($words[2]) . "</td>";
                echo "<td>" . htmlspecialchars($words[3]) . "</td>";
                echo "<td>" . htmlspecialchars($words[8]) . " " . reformatDate(htmlspecialchars($words[9])) . "." . date_format($date, "Y") . " " . htmlspecialchars($words[10]) . "</td>";

                echo "</tr>";
            }

            echo "</tbody></table>";

            echo "<br>";

            clearstatcache();
        }
    }
}

?>
