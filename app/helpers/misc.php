<?php

function reformatDate($date)
{
    if (empty($date)) {
        return "";
    }

    $dateElements = explode('/', $date);

    return ($dateElements[1] . "." . $dateElements[0]);
}

function translateRoleDescriptions($role)
{
    $returnValue = "";

    switch ($role) {
        case "Administrator":
            $returnValue = "Administrator";
            break;
        case "User":
            $returnValue = "Korisnik";
            break;
        case "Guest":
            $returnValue = "Gost";
            break;
        default:
            $returnValue = $role;
            break;
    }

    return $returnValue;

}
