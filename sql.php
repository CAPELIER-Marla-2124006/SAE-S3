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

// TODO: penser à faire un traitement avant requette

// query db
$response = $db->prepare($request);
$response->execute();

// store result in result
$result = $response->fetchAll();

// if there is no result, print cheh
if(!isset($result[0])) {
    echo "Cheh";
    exit();
}

// create table and headers of table
echo "<table><tr>";
// for each keys in first result
foreach ($result[0] as $key => $caca) {
    // if a string, echo in header list
    if(ctype_alpha($key)) echo "<th>".$key."</th>";
}
echo "</tr>";

// fill table
// for each line in results
foreach ($result as $line) {
    echo "<tr>";
    // for each case in line
    foreach ($line as $key => $case) {
        // if a string, echo case
        if(ctype_alpha($key)) echo "<td>".$case."</td>";
    }
    echo "</tr>";
}
//end table
echo "</table>";

//echo($result);
?>
