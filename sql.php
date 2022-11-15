<?php
require "db.php";

//debug
//echo("P " . $_POST['request']. PHP_EOL . "G " . $_GET['request']. PHP_EOL . "R " . $_REQUEST['request']);

// get request
if(isset($_REQUEST['request'])) $request = $_REQUEST['request'];
else {
    echo('Roquette REQUEST vide');
    exit();
}

// query db
$response = $db->prepare($request);
$response->execute();

// encode repsonse in json and store in result
$result = json_encode($response->fetchAll());

echo($result);
?>
