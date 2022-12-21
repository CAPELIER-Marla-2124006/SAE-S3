<?php
require("sensible.php");
global $db;
$db = carefulConnectDB();
session_start();

function updateDB($column, $value) {
    global $db;
    $ps = $db->prepare("UPDATE USERS SET `?` =? where USERS.`id`=?");

    print($_SESSION["id"]);
    print($column);
    print($value);

    $ps->bindParam(1, $column, PDO::PARAM_STR);
    $ps->bindParam(2, $value, PDO::PARAM_STR);
    $ps->bindParam(3, $_SESSION["id"], PDO::PARAM_INT);
    $ps->execute();
    return($ps->fetchAll());
}
