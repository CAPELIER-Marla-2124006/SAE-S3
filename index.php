<?php
// Declare variables
$username;
$notes;
$levels;
$colorHue;
$connexionError;

// Include login.php file that contains isLogged function
include ($_SERVER['DOCUMENT_ROOT']."/php/login.php");
// Check if the user is logged in, this var can be edited after in case of a problem, or if the user want to be disconected
$logged = isLogged();

// Import the function to connect to DB
require("php/db.php");

// Check if the request method is POST and the type variable is set in the POST request
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['type'])) {
    // Check the type of POST request
    switch($_POST['type']) {
        case "connect":
            // Connect to the database
            $db = connectDB("IUT-SAE");
            // Prepare and execute a SELECT statement to retrieve the password for the given username
            $ps = $db->prepare("SELECT id, passwd from USERS where username=?");
            $ps->bindParam(1, $_POST["username"]);
            $ps->execute();
            // If a row is returned, verify the password
            if($row = $ps->fetch()) {
                if(password_verify($_POST["password"], $row["passwd"])) {
                    // Start a new session and set session variables
                    session_start();
                    $_SESSION["logintime"] = time();
                    $_SESSION["id"] = $row["id"];
                    $logged = true;
                    //$connexionError = "reussi";
                } else {
                    // Set an error message for invalid password
                    $connexionError = "Mauvais mot de passe";
                }
            } else {
                // Set an error message for unrecognized username
                $connexionError = "Utilisateur non reconnu";
            }
        break;
        case "register":
            // List of forbidden usernames
            $forbidden_username = ["index", "login", "register", "account", "sounds", "css", "img", "js", "php"];
            // Connect to the database
            $db = connectDB("IUT-SAE");
            // Check if the chosen username is already taken
            $ps = $db->prepare("SELECT id from USERS where username=?");
            $ps->bindParam(1, $_POST["username"]);
            $ps->execute();
            // If a row is returned, the username is taken
            if($row = $ps->fetch()) {
                $connexionError = "Ce nom d'utilisateur existe déjà";
                // If the username is in the list of forbidden usernames or starts with a period, it is not available
            } else if(in_array($_POST["username"],$forbidden_username) || $_POST["username"][0] == ".") {
                $connexionError = "Ce nom d'utilisateur n'est pas disponible";
                // If the passwords don't match, set an error message
            } else if($_POST["password"] != "" && $_POST["password"] != $_POST["confirm-password"]) {
                $connexionError = "Les mots de passe ne correspondent pas";
            } else {
                // Include the sensible.php file to edit DB (need to be careful with this)
                require("php/sensible.php");
                // Hash the password
                $hash = password_hash($_POST["password"], PASSWORD_BCRYPT);
                // Connect to the database
                $db = carefulConnectDB();
                // Insert the new user into the USERS table
                $ps = $db->prepare("INSERT INTO USERS (username, passwd, notes) VALUES (?, ?, \"Notes pour plus tard\")");
                $ps->bindParam(1, $_POST["username"]);
                $ps->bindParam(2, $hash);
                $ps->execute();
                // If the insert was successful, retrieve the new user's id and set it as a session variable
                if($ps->rowCount()==1) {
                    $ps = $db->prepare("SELECT id from USERS where username=?");
                    $ps->bindParam(1, $_POST["username"]);
                    $ps->execute();
                    if($row = $ps->fetch()) {
                        session_start();
                        $_SESSION["logintime"] = time();
                        $_SESSION["id"] = $row["id"];
                        $logged = true;
                    }
                } else {
                    // Set an error message for account creation failure
                    $connexionError = "Erreur de création du compte";
                }
            }
        break;
        case "disconnect":
            // Destroy the session and unset the PHPSESSID cookie
            session_destroy();
            unset($_COOKIE["PHPSESSID"]);
            $logged = false;
            break;
        default:
        break;
    }
}

        // If the user is logged in, retrieve their username, notes, levels, and colorHue from the database
        if($logged){
            $db = connectDB("IUT-SAE");
            $ps = $db->prepare("SELECT `username`, `notes`, `levels`, `colorHue`, `points` FROM USERS WHERE id=?");
            $ps->bindParam(1, $_SESSION["id"]);
            $ps->execute();

            if($row = $ps->fetchAll()) {
                // Set the retrieved values to the corresponding variables
                $username = $row[0]["username"];
                $notes = $row[0]["notes"];
                $levels = $row[0]["levels"];
                $colorHue = $row[0]["colorHue"];
                $userPoints = $row[0]["points"];
            }
        }
?>
<!DOCTYPE html>
<html lang="fr">

<head>

    <title>MySQLearn</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style/style.css">
    <script src="ace/ace.js"></script>
    <script src="script.js" defer></script>

</head>


<body>

    <div class="mobile-warning">
        <h1>Désolé, ce site ne peut être utilisé que sur un plus grand écran.</h1>
    </div>


    <div class="header">
        <?php
            // set the level of the user (js script will get it)
            if(isset($levels))
                echo('<div id="levelNumber" style="display: none;">'.$levels.'</div>');
            else
                echo('<div id="levelNumber" style="display: none;">1</div>');
        ?>
        <div class="webName">
            <img src="/images/wlicon.png" alt="MySQLearn logo" height="38" width="38">
            <h1>MySQLearn</h1>
        </div>
        <?php
            // set color of the page in the slider
            if (isset($colorHue))
                echo('<input type="range" min="0" max="360" class="slider" id="colorSlider" value="'.$colorHue.'">');
            else
                echo('<input type="range" min="0" max="360" class="slider" id="colorSlider" value="164">');
        ?>

        <select class="levels" id="levels">
        <?php
            // add options of level, start at 1 and have a loop fromp 2
            echo('<option value="1" class="level" id="level1">Level 1</option>');
            $i = 2;
            // if the user is connected, the var $levels contains the level the user is at,
            // so we disable only the levels the user cannot access to
            if(isset($levels)) {
                // avaliable levels
                while($i <= $levels && $i <= 10) {
                    echo('<option value="'.$i.'" class="level" id="level'.$i.'">Level '.$i.'</option>');
                    $i++;
                }
                // disabled levels
                while($i <= 10) {
                    echo('<option value="'.$i.'" class="level" id="level'.$i.'" disabled>Level '.$i.'</option>');
                    $i++;
                }
            // not connected so only level 1 is enabled
            } else {
                while($i <= 10) {
                    echo('<option value="'.$i.'" class="level" id="level'.$i.'" disabled>Level '.$i.'</option>');
                    $i++;
                }
            }
        ?>
        </select>
        <div class="connect">
        <?php
        // display the div to disconnect if the user is connected
        if(isset($username)) {

            echo('<div class="connexion">
                <button id="accountButton">'.$username.'</button>
                <form action="/index.php" method="post" id="accountForm">
                    '.(isset($connexionError)? print("<h2>".$connexionError."</h2>"):"").'
                    <h1 id="userPoints">Points : '.$userPoints.'</h1>
                    <input type="hidden" name="type" value="disconnect">
                    <input type="submit" value="Se déconnecter">
                </form>
                 </div>');
        // or display conexion and register buttons
        } else {
            echo('<div class="connexion">
                <button id="connexionButton">Connexion</button>
                <form action="/index.php" method="post" id="connexionForm">
                    <h1>Connection</h1>
                    '.(isset($connexionError)? print("<h2>".$connexionError."</h2>"):"").'
                    <fieldset>
                        <legend>Nom d\'utilisateur</legend>
                        <input type="text" name="username" id="connexionUsername">
                    </fieldset>
                    <fieldset>
                        <legend>Mot de passe</legend>
                        <input type="password" name="password" id="connexionPassword">
                    </fieldset>
                    <input type="hidden" name="type" value="connect">
                    <input type="submit" value="Se connecter">
                </form>
            </div>

            <div class="connexion">
                <button id="registerButton">Inscription</button>
                <form action="/index.php" method="post" id="registerForm">
                    <h1>Inscription</h1>
                    '.(isset($connexionError)? print('<h2>'.$connexionError.'</h2>'):"").'
                    <fieldset>
                        <legend>Nom d\'utilisateur</legend>
                        <input type="text" name="username" id="registerUsername">
                    </fieldset>
                    <fieldset>
                        <legend>Mot de passe</legend>
                        <input type="password" name="password" id="registerPassword">
                    </fieldset>
                    <fieldset>
                        <legend>Confirmer le mot de passe</legend>
                        <input type="password" name="confirm-password" id="confirmPassword">
                        </fieldset>
                    <input type="hidden" name="type" value="register">
                    <input type="submit" value="S\'inscrire">
                </form>
            </div>');
        }
        ?>



        </div>
    </div>


    <div class="page">

        <div class="left">

            <fieldset class="todo">
                <legend>Instructions</legend>
                <div class="instructions" id="instructions">
                </div>
                <button id="lesson"><img src="/images/lesson.svg" alt="lesson" width="32"></button>
                <button id="hint"><img src="/images/hint.svg" alt="hint" width="32"></button>
            </fieldset>

            <div class="verticalResizer resizer" id="verticalResizerLeft"></div>

            <fieldset class="notes">
                <legend>Notes</legend>
                <?php
                // add the notes stored in DB if user is connected
                if(isset($notes))
                    echo('<textarea name="notes" id="notes">'.$notes.'</textarea>');
                else
                    echo('<textarea name="notes" id="notes">Notes pour plus tard</textarea>');
                ?>

            </fieldset>

        </div>

        <div class="horizontalResizer resizer" id="horizontalResizer"></div>

        <div class="right">
            <div class="code-buttons">
                <fieldset class="code">
                    <legend>Code</legend>
                    <div id="code-container">
                        <div id="code-editor"><?php
                            // connect to DB to get default code for the level the user is at
                            $db = connectDB("IUT-SAE");
                            $ps = $db->prepare("SELECT codeInit FROM EXERCICES where idLevel=?");
                            if(isset($levels)) {
                                $ps->bindParam(1, $levels);
                            } else {
                                $n = 1;
                                $ps->bindParam(1, $n);
                            }
                            $ps->execute();
                            if($row = $ps->fetch()) {
                                echo $row[0];
                            } else {
                                echo ("--insérer du code ici\nSELECT * FROM ENQUETE01;");
                            }
                            ?></div>
                    </div>
                </fieldset>

                <div class="buttons">
                    <button id="restart">Restart</button>
                    <button id="execute">Execute</button>
                </div>
            </div>


            <div class="verticalResizer resizer" id="verticalResizerRight"></div>

            <fieldset class="results">
                <legend>Results</legend>
                <div id="results"></div>
            </fieldset>

        </div>


        <div class="popupBackground">

            <div id="popupLesson">
                <button class="exitButton"><img src="/images/exit.png" alt="close page"></button>
                <h1>Cours</h1>
                <div class="text"></div>
            </div>


            <div id="popupHint">
                <button class="exitButton"><img src="/images/exit.png" alt="close page"></button>
                <h1>Indice</h1>
                <div class="text"></div>
            </div>


            <div id="popupEnd">
                <button class="nextButton"><img src="/images/next.png" alt="next level"></button>
                <h1>Terminé !</h1>
                <div class="text"></div>
                <div class="reminder">N'oublie pas d'écrire ce que t'as écrit dans tes notes pour ne rien oublier !</div>
            </div>

        </div>
    </div>

</body>

</html>
