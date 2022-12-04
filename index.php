<?php
    $username;
    $notes;
    $levels;
    $colorHue;
    require("db.php");

    if($_SERVER['REQUEST_METHOD'] == "POST") {
        switch($_POST['type']) {
            case "connect":
                $db = connectDB("IUT-SAE");
                $ps = $db->prepare("SELECT id, passwd from USERS where username=?");
                $ps->bindParam(1, $_POST["username"]);
                $ps->execute();
                if($row = $ps->fetch()) {
                    if(password_verify($_POST["password"], $row["passwd"])) {
                        session_start();
                        $_SESSION["logintime"] = time();
                        $_SESSION["id"] = $row["id"];
                    } else {
                        $connexionError = "Mauvais mot de passe";
                    }
                } else {
                    $connexionError = "Utilisateur non reconnu";
                }
                break;
            case "register":
                $db = connectDB("IUT-SAE");
                $ps = $db->prepare("SELECT id from USERS where username=?");
                $ps->bindParam(1, $_POST["username"]);
                $ps->execute();
                if($row = $ps->fetch()) {
                    $connexionError = "Ce nom d'utilisateur existe déjà";
                }
                $forbidden_username = ["index", "login", "register", "account", "sounds", "css", "img", "js", "php"];
                if(in_array($_POST["username"],$forbidden_username) || $_POST["username"][0] == ".") {
                    $connexionError = "Ce nom d'utilisateur n'est pas disponible";
                }
                if($_POST["password"] != "" && $_POST["password"] == $_POST["confirm-password"]) {
                    $connexionError = "Les mots de passe ne correspondent pas";
                }

                $hash = password_hash($_POST["password"], PASSWORD_BCRYPT);
                $db = carefulConnectDB();
                $ps = $db->prepare("INSERT INTO USERS (username, passwd, notes) VALUES (?, ?, \"Notes pour plus tard\")");
                $ps->bindParam(1, $_POST["username"]);
                $ps->bindParam(2, $hash);
                $ps->execute();
                if($ps->rowCount()==1) {
                    $ps = $db->prepare("SELECT id from USERS where username=?");
                    $ps->bindParam(1, $_POST["username"]);
                    $ps->execute();
                    if($row = $ps->fetch()) {
                        session_start();
                        $_SESSION["logintime"] = time();
                        $_SESSION["id"] = $row["id"];
                    }
                } else {
                    $connexionError = "Erreur de création du compte";
                }
                break;
            case "disconnect":
                session_destroy();
                unset($_COOKIE["PHPSESSID"]);
            default:
                break;
        }
    }

    include ($_SERVER['DOCUMENT_ROOT']."/php/login.php");
    $logged = isLogged();

    if($logged){
        $db = connectDB("IUT-SAE");
        $ps = $db->prepare("SELECT username, notes, 'levels', colorHue FROM USERS WHERE id=?");
        $ps->bindParam(1, $_SESSION["id"]);
        $ps->execute();

        if($row = $ps->fetchAll()) {
            //print_r($row[0]);
            $username = $row[0]["username"];
            $notes = $row[0]["notes"];
            $levels = $row[0]["levels"];
            $colorHue = $row[0]["colorHue"];
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">

<head>

    <title>MySQLearn</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style/style.css">
    <script src="ace/ace.js" type="text/javascript" charset="utf-8"></script>
    <script src="script.js" type="module" charset="utf-8" defer></script>

</head>


<body>

    <div class="mobile-warning">
        <h1>Désolé, ce site ne peut être utilisé que sur un plus grand écran.</h1>
    </div>


    <div class="header">
        <?php
            if(isset($levels))
                echo('<div id="levelNumber" style="display: none;">'.$levels.'</div>');
            else
                echo('<div id="levelNumber" style="display: none;">1</div>');
        ?>
        <h1>MySQLearn</h1>
        <?php
            if (isset($colorHue))
                echo('<input type="range" min="0" max="360" class="slider" id="colorSlider" value="'.$colorHue.'">');
            else
                echo('<input type="range" min="0" max="360" class="slider" id="colorSlider" value="164">');
        ?>

        <select class="levels" id="levels">
        <?php
            $i = 2;
            echo('<option value="1" class="level" id="level1" type="button">Level 1</option>');
            if(isset($levels)) {
                while($i <= $levels && $i <= 10) {
                    echo('<option value="'.$i.'" class="level" id="level'.$i.'" type="button">Level '.$i.'</option>\n');
                    $i++;
                }
                while($i <= 10) {
                    echo('<option value="'.$i.'" class="level" id="level'.$i.'" type="button" disabled>Level '.$i.'</option>\n');
                    $i++;
                }
            } else {
                while($i <= 10) {
                    echo('<option value="'.$i.'" class="level" id="level'.$i.'" type="button" disabled>Level '.$i.'</option>\n');
                    $i++;
                }
            }
        ?>
        </select>
        <div class="connect">
        <?php
        if(isset($username)) {

            echo('<div class="connexion">
                <button id="accountButton">'.$username.'</button>
                <form action="/index.php" method="post" id="accountForm">
                    <input type="hidden" name="type" value="disconnect">
                    <input type="submit" value="Se déconnecter">
                </form>
                 </div>');
        } else {
            echo('<div class="connexion">
                <button id="connexionButton">Connexion</button>
                <form action="/index.php" method="post" id="connexionForm">
                    <h1>Connection</h1>
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
                        <input type="password" name="confirm-password" id="registerPassword">
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
                <button id="lesson"><img src="/images/lesson.svg" alt="lesson" width="32px"></button>
                <button id="hint"><img src="/images/hint.svg" alt="hint" width="32px"></button>
            </fieldset>

            <div class="verticalResizer resizer" id="verticalResizerLeft" resizeDirection="vertical"></div>

            <fieldset class="notes">
                <legend>Notes</legend>
                <?php
                if(isset($notes))
                    echo('<textarea name="notes" id="notes">'.$notes.'</textarea>');
                else
                    echo('<textarea name="notes" id="notes">Notes pour plus tard</textarea>');
                ?>

            </fieldset>

        </div>

        <div class="horizontalResizer resizer" id="horizontalResizer" resizeDirection="horizontal"></div>

        <div class="right">
            <div class="code-buttons">
                <fieldset class="code">
                    <legend>Code</legend>
                    <div id="code-container">
                        <div id="code-editor"><?php
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
                            echo "--insérer du code ici\nSELECT * FROM ENQUETE01;";
                        }
                        ?></div>
                    </div>
                </fieldset>

                <div class="buttons">
                    <button id="restart">Restart</button>
                    <button id="execute">Execute</button>
                </div>
            </div>


            <div class="verticalResizer resizer" id="verticalResizerRight" resizeDirection="vertical"></div>

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
            </div>

        </div>
    </div>

</body>

</html>
