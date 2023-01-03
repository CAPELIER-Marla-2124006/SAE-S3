<?php
require("sensible.php");
global $db;
$db = carefulConnectDB();
session_start();

function updateDB($column, $value) {
    global $db;
    global $ps;

    switch ($column) {
        case 'levels':
            $ps = $db->prepare("UPDATE USERS SET levels =? where USERS.`id`=?");
            /* print("levels"); */
            break;

        case 'notes':
            $ps = $db->prepare("UPDATE USERS SET notes =? where USERS.`id`=?");
            /* print("notes"); */
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

//
if(isset($_REQUEST["column"])) {
    print_r($_REQUEST);
    updateDB($_REQUEST["column"], $_REQUEST["value"]);
}
