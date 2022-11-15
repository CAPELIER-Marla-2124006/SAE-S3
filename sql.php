<?php
require "db.php";

// get request
if(isset($_POST['request'])) $request = $_POST['request'];
else {
    echo('Roquette vide');
    exit();
}

// query db
$ps = $db->prepare($request);
$ps->execute();

// encode repsonse in json and store in result
$result = json_encode($ps->fetchAll());

echo($result);
?>
