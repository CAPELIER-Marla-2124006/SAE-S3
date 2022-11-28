<?php
require "db.php";

$MAX_LEVELS = 10;

//debug
//echo("P " . $_POST['request']. PHP_EOL . "G " . $_GET['request']. PHP_EOL . "R " . $_REQUEST['request']);

// get request
if(isset($_REQUEST['request'])) $request = $_REQUEST['request'];
else {
    echo('Requette REQUEST vide');
    exit();
}
// verify request conatin level number
if(isset($_REQUEST['level']) && $_REQUEST['level'] <= $MAX_LEVELS && $_REQUEST['level'] >= 1)
    $level_id = $_REQUEST['level'];
else {
    echo('Erreur: niveau non valide');
    exit();
}

// TODO: penser à faire un traitement avant requette

// get db connexion from function
$db = connectDB("IUT-SAE-EX" . $level_id);

// query db
$response = $db->prepare($request);
$response->execute();

// store result in result
$result = $response->fetchAll();

//DEBUG
/* echo "<!--";
print_r ($result);
echo json_encode($result);
echo "-->\n";
 */

// if there is no result, print cheh
if(!isset($result[0])) {
    echo "Aucun resultat";
    exit();
}

// create table and headers of table
echo "<table><tr>";
// for each keys in first result
foreach ($result[0] as $key => $caca) {
    // if it's not a number
    if(!ctype_digit($key) && !is_numeric($key)) echo "<th>".$key."</th>";
}
echo "</tr>";

// fill table
// for each line in results
foreach ($result as $line) {
    echo "<tr>";
    // for each case in line
    foreach ($line as $key => $case) {
        // if it's not a number
        if(!ctype_digit($key) && !is_numeric($key)) echo "<td>".$case."</td>";
    }
    echo "</tr>";
}
//end table
echo "</table>";

//echo($result);
?>
