<?php
require("db.php");
$db = carefulConnectDB("IUT-SAE");
$ps = $db->prepare("UPDATE USERS SET `?` =? where USERS.`id`=?");

session_start();
if (!isset($_GET["type"])) exit();

print_r($_GET);
print_r($_SESSION);

if (isset($_GET["type"])) {
    $ps->bindParam(1, $_GET["type"], PDO::PARAM_STR);
    $ps->bindParam(2, $_GET["value"], PDO::PARAM_STR);
    $ps->bindParam(3, $_SESSION["id"], PDO::PARAM_INT);
    $ps->execute();
}
print_r($ps->fetchAll());
