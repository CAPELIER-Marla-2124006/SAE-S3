<?php
require("sensible.php");
global $db;
$db = carefulConnectDB();
session_start();

/**
 * Update DB from the data sent from js
 * @param string $column name the column to edit
 * @param string $value the value to put in the right place
 * @return array what the DB anwser after executing the update
 */
function updateDB($column, $value) {
    global $db;
    global $ps;

    switch ($column) {
        case 'levels':
            $ps = $db->prepare("UPDATE USERS SET levels =? where USERS.`id`=?");
            break;

        case 'notes':
            $ps = $db->prepare("UPDATE USERS SET notes =? where USERS.`id`=?");
            break;

        case 'colorHue':
            $ps = $db->prepare("UPDATE USERS SET colorHue =? where USERS.`id`=?");
            break;

        case 'points':
            $ps = $db->prepare("UPDATE USERS SET points =? WHERE USERS.`id` =?");
            break;

        default:
            print("error");
            break;
    }


    /* print($_SESSION["id"]."\n");
    print($column."\n");
    print($value."\n"); */

    //$ps->bindParam(1, $column , PDO::PARAM_STR);
    $ps->bindParam(1, $value/* , PDO::PARAM_STR */);
    $ps->bindParam(2, $_SESSION["id"], PDO::PARAM_INT);
    $ps->execute();
    return($ps->fetchAll());
}

/// MAIN ///
// If the page has been requested from JS with values, execute the function with the vars getted
if(isset($_REQUEST["column"])) {
    print_r($_REQUEST);
    updateDB($_REQUEST["column"], $_REQUEST["value"]);
}
