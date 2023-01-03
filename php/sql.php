<?php
require "db.php";
require "update.php";

$MAX_LEVELS = 10;
$DB_NAME = "IUT-SAE";
$ALL_TYPES = ["ex", "codeInit", "instructions", "lesson", "hint", "success"];
$TYPE;
$ID_LEVEL;
$REQUEST;

//debug
//echo("P " . $_POST['request']. PHP_EOL . "G " . $_GET['request']. PHP_EOL . "R " . $_REQUEST['request']);
/* echo "<!--";
print_r($_REQUEST);
echo "-->"; */

// verify numbers of arguments and that are true arguments
function checkArguments() {

    global $MAX_LEVELS, $ALL_TYPES, $TYPE, $ID_LEVEL, $REQUEST;

    // if idLevel doesn't exist or is incoherent
    if(!isset($_REQUEST["idLevel"]) ||
       ($_REQUEST["idLevel"] < 1 && $_REQUEST["idLevel"] > $MAX_LEVELS)) {

        echo "Mauvaise requette de niveau";
        exit();

    }

    // if type isnn't correct
    if(!isset($_REQUEST["type"]) || !in_array($_REQUEST["type"], $ALL_TYPES)) {

        echo "Mauvaise demande de type";
        exit();

    }

    // if type is ex -> need request
    if($_REQUEST["type"] == "ex") {
        if(!isset($_REQUEST["request"])) {
            echo "La demande d'exercice a besoin d'une requette";
            exit();
        } else {
            $REQUEST = $_REQUEST["request"];
        }
    }

    // if everything is fine edit $TYPE and $ID_LEVEL
    $TYPE = $_REQUEST["type"];
    $ID_LEVEL = $_REQUEST["idLevel"];
}

// request from user
function exercice($idLevel, $userRequest) {
    // get db connexion from function
    global $DB_NAME, $ID_LEVEL;

    // connect to first DB to get request the user have to do
    $db = connectDB($DB_NAME);
    $response = $db->prepare("SELECT response FROM EXERCICES where idLevel=".$idLevel);
    $response->execute();
    // $levelRequestResponse store what the user should do
    $levelRequestResponse = $response->fetchAll();
    $levelRequestResponse = $levelRequestResponse[0][0];


    $db = connectDB($DB_NAME . "-EX" . $idLevel);

    $response = $db->prepare($levelRequestResponse);
    $response->execute();
    // $levelResponse store the right result
    $levelResponse = $response->fetchAll();

    // query db
    $response = $db->prepare($userRequest);
    $response->execute();

    // store result in result
    $result = $response->fetchAll();

    if($result == $levelResponse) {
        echo "true\n";

        $db = connectDB($DB_NAME);

        $userLevel = $db->prepare("SELECT `levels` from USERS where id=?");
        $userLevel->bindParam(1, $_SESSION["id"]);
        $userLevel->execute();
        $response = $userLevel->fetchAll();


        $userLevel = $response[0][0];
        // if the user won a new level
        if($ID_LEVEL == $userLevel) {
            /* print_r( */updateDB("levels", $userLevel+1)/* ) */;
        }
    } else {
        echo "false\n";
    }

    //DEBUG
    /* echo "<!--";
    print_r ($result);
    echo json_encode($result);
    echo "-->\n"; */

    // if there is no result, print no results
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

}

// get basic text from code, lesson, hint and success
function getTexts($idLevel, $type) {

    //get global variables
    global $DB_NAME;

    // send request to db
    $db = connectDB($DB_NAME);
    $response = $db->prepare("SELECT " . $type . " from EXERCICES where idLevel=".$idLevel);
    $response->execute();
    $result = $response->fetchAll();

    // debug
    /* echo "<!--";
    print_r($result);
    echo "-->"; */

    // print result
    echo $result[0][0];
}

// get instructions and tables
function getInstructions($idLevel) {

    //get global variables
    global $DB_NAME;

    // ask db the instructions of the level
    $db = connectDB($DB_NAME);
    $response = $db->prepare("SELECT instructions from EXERCICES where idLevel=".$idLevel);
    $response->execute();
    $instructions = $response->fetchAll();

    // debug
    /* echo "<!--";
    print_r($instructions);
    echo "-->"; */

    // get instruction text and print it
    $instructions = $instructions[0][0];
    if($instructions == "") exit();
    echo $instructions."</br></br> Schéma des tables : </br>";

    // prepare to print tables fields
    //echo $DB_NAME."-EX".$idLevel;
    $db = connectDB($DB_NAME."-EX".$idLevel);
    $response = $db->prepare("show tables");
    $response->execute();
    $tables = $response->fetchAll();
    $tables = $tables[0];

    // debug
    /* echo "<!--";
    print_r($tables);

    print_r($tables);
    echo "--></br>"; */

    // for each table in database, get columns names and types and print it
    foreach($tables as $caseName => $tableName) {

        if(!ctype_digit($caseName) && !is_numeric($caseName)) {

            echo "</br>Table <b>".$tableName."</b> (</br>";

            // get columns from table
            $response = $db->prepare("SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".$DB_NAME."-EX".$idLevel."' AND TABLE_NAME = '".$tableName."'");
            $response->execute();
            $columns = $response->fetchAll();

            // debug
            /* echo "<!--";
            print_r($columns);
            //$columns = $columns[0];
            //print_r($columns);
            echo "-->"; */

            foreach($columns as $line) {
                echo "&emsp;".$line[0].": ";
                echo "<i>".$line[1]."</i>;</br>";
            }
            // end of one table in the db
            echo ");</br>";
        }
    }
}

///MAIN///
// will get the arguments passed in the request (get) and store them in global vars
checkArguments();

//echo "debug : type=".$TYPE;

// when we got type, we call function that will understand it
switch ($TYPE) {
    // if the request is from an exercice, we will do the request the user typed
    case "ex":
        exercice($ID_LEVEL, $REQUEST);
        break;
    // if the request is the instructions, we need the text AND tables schemes in db
    case "instructions":
        getInstructions($ID_LEVEL);
        break;
    // for the other commands, we just print the text in for the exercice
    case "codeInit":
    case "lesson":
    case "hint":
    case "success":
        getTexts($ID_LEVEL, $TYPE);
        break;
    // shouldn't be there
    default:
        echo "bug";
        break;
}



?>
