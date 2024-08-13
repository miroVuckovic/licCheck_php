<?php

function reformatDate($date)
{
    if (empty($date)) {
        return "";
    }

    $dateElements = explode('/', $date);

    return ($dateElements[1] . "." . $dateElements[0]);
}

?>
