<!DOCTYPE html>
<html lang="fr">

<head>

	<title>MySQLearn</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="/style/style.css">
	<script src="/ace/ace.js" defer></script>
	<script src="/script/game.js" defer></script>
    <script src="/script/page.js"></script>

</head>


<body>

    <div class="mobile-warning">
        <h1>Désolé, ce site ne peut être utilisé que sur un plus grand écran.</h1>
    </div>


    <div class="header">
        <div class="webName">
            <img src="/images/wlicon.png" alt="MySQLearn logo" height="38" width="38">
            <h1>MySQLearn</h1>
        </div>

        <label for="colorSlider" class="uselessLabel">Choix de la couleur :</label>
        <input type="range" min="0" max="360" class="slider" id="colorSlider" value="<?= $A_view['colorHue'] ?>">

        <label for="levels" class="uselessLabel">Choix du niveau</label>
        <select class="levels" id="levels">
			<?php
			// add options of level, start at 1 and have a loop from 2
			echo('<option value="1" class="level" id="level1">Level 1</option>');
			$i = 2;
			// if the user is connected, the var $levels contains the level the user is at,
			// so we disable only the levels the user cannot access to
			if(!isset($A_view['levels'])) {
				// not connected so only level 1 is enabled
				while ($i <= $A_view['maxLevels']) {
					echo('<option value="' . $i . '" class="level" id="level' . $i . '" disabled>Level ' . $i . '</option>');
					$i++;
				}
			} else {
				// available levels
				while ($i <= $A_view['levels'] && $i <= $A_view['maxLevels']) {
					echo('<option value="' . $i . '" class="level" id="level' . $i . '">Level ' . $i . '</option>');
					$i++;
				}
				// disabled levels
				while ($i <= $A_view['maxLevels']) {
					echo('<option value="' . $i . '" class="level" id="level' . $i . '" disabled>Level ' . $i . '</option>');
					$i++;
				}
			}
			?>
        </select>

        <div class="connect">
			<?php
			// display the div to disconnect if the user is connected
			if(isset($A_view['username'])) {

				echo('<div class="connexion">
                    <button id="accountButton">'.$A_view['username'].'</button>
                    <h1 id="userPoints">Points : '.$A_view['userPoints'].'</h1>
                    <a href="/disconnect">Se déconnecter</a>
                 </div>');
				// or display connexion and register buttons
			} else {
				echo('<div class="connexion">
                <button id="connexionButton">Connexion</button>
                <form action="/login" method="post" id="connexionForm">
                    <h1>Connection</h1>
                    <fieldset>
                        <legend>Nom d\'utilisateur</legend>
                        <input type="text" name="username" id="connexionUsername">
                    </fieldset>
                    <fieldset>
                        <legend>Mot de passe</legend>
                        <input type="password" name="password" id="connexionPassword">
                    </fieldset>
                    <input type="submit" value="Se connecter">
                </form>
            </div>

            <div class="connexion">
                <button id="registerButton">Inscription</button>
                <form action="/register" method="post" id="registerForm">
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
                        <input type="password" name="confirm-password" id="confirmPassword">
                        </fieldset>
                    <input type="submit" value="S\'inscrire">
                </form>
            </div>');
			}
			?>
        </div>
    </div>


    <main>

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
                <textarea name="notes" id="notes"><?= $A_view['notes'] ?></textarea>
            </fieldset>

        </div>


        <div class="horizontalResizer resizer" id="horizontalResizer"></div>


        <div class="right">

            <div class="code-buttons">
                <fieldset class="code">
                    <legend>Code</legend>
                    <div id="code-container">
                        <div id="code-editor"><?= $A_view['code'] ?></div>
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


            <div id="popupTheEnd">
                <button class="exitButton"><img src="/images/exit.png" alt="close page"></button>
                <h1>Fin du jeu !</h1>
                <div class="text">Merci d'avoir joué à ce jeu, j'espère que tu as bien compris comment le MySQL fonctionne. Si tu veux, tu peux recommencer le jeu et essayer d'avoir plus de points.</div>
            </div>


            <div id="popupEnd">
                <button class="nextButton"><img src="/images/next.png" alt="next level"></button>
                <h1>Terminé !</h1>
                <div class="text"></div>
                <div class="reminder">N'oublie pas d'écrire ce que t'as écrit dans tes notes pour ne rien oublier !</div>
            </div>

        </div>

    </main>

</body>