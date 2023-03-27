<?php

class V1Controller extends AController {

    /**
	 * Process the request by using the url (used to change the view's elements and update the database)
     * @return void
	 */
    public function process(): void {
		Session::resume_session();
        switch ($this->urlFolder) {
            case 'exercise':{ // get exercise from database and return it in json
                $data = new DataAccess(Model::getAdminConnexion());
                $exercise = $data->getExercise($this->urlParams[0]);//urlParams[0] = nb level
                $php_array = [
                    'lesson'=>$exercise->getLesson(),
                    'instructions'=>$exercise->getInstructions(),
                    'hint'=>$exercise->getHint(),
                    'codeInit'=>$exercise->getCodeInit(),
                    'success'=>$exercise->getSuccess()
                ];
                $json_array = json_encode($php_array);
                echo($json_array);
                break;
            }
            case 'saveUser':{ // save/update user's stats in database
                $data = new DataAccess(Model::getAdminConnexion());
                $user = $data->getUser($_SESSION['ID']);
                if(isset($this->postParams['notes'])) {
                    $user->setNotes($this->postParams['notes']);
                }
                if(isset($this->postParams['colorHue'])) {
                    $user->setColorHue($this->postParams['colorHue']);
                }
                if(isset($this->postParams['points'])) {
                    $user->setPoints($this->postParams['points']);
                }
                $data->updateUser($user);
                break;
            }
            case 'submit':{ // check if user's answer is equal to the right answer, update user's stats and return result in json
                $dataExercise = new DataAccess(Model::getUserConnexion()); // connect as not Admin
                $dataAdmin = new DataAccess(Model::getAdminConnexion()); // connect as Admin

                // get current connected user if possible
                if(Session::is_login())
                    $user = $dataAdmin->getUser($_SESSION['ID']);

                // get exercise object of the exrcise asked
                $exerciseAsked = $dataAdmin->getExercise($this->urlParams[0]);

                // user answer send in post
                $userAnswer = $this->postParams['answer'];
				// and the result given by database
				$userResult = array();

                // get right answer of the exercise asked
                $rightAnswer = $exerciseAsked->getExercise_answer();

				// create a table to display results
				$table="";

                // check if there is an error in the user answer
                try {
                    // get result array of the user answer
                    $userResult = $dataExercise->executeExerciseAnswer($userAnswer);
                    for($i=0; $i < sizeof($userResult); $i++) {
                        foreach ($userResult[$i] as $key => $value) {
                            $userResult[$i][strtoupper($key)] = $value;
                        }
                    }
                }catch (Exception $e){ // if there is an error in the user answer send it
                    $table = "Error in your code : ". $e->getMessage(). "\n";
                }

                // check if user answer is equal to right answer and update user's stats
                if($userResult == $dataAdmin->executeExerciseAnswer($rightAnswer)){ // if user answer is equal to right answer
                    $win = true;
                    $points = 0;
                    if(Session::is_login()) {
                        $points = $user->getPoints() + $exerciseAsked->getPoints();
                        $user->setLevel($this->postParams['level']);
                    }
                }else{ // if user answer is not equal to right answer
                    $win = false;
                    $points = 0;
                    if(Session::is_login()){
                        $points = $user->getPoints() - intdiv($exerciseAsked->getPoints(), 5);
                    }
                }

                if(Session::is_login()){ // if user is connected
                    $user->setPoints($points);
                    $dataAdmin->updateUser($user);
                }

                // create table to display result
                if(empty($table) && ($userResult == null || sizeof($userResult)==0)) {// if user's answer is empty display no response
					$table = "Aucune réponse";
                } elseif(empty($table)) {//else display result in table

                    // create table and headers of table
                    $table = "<table><tr>";

                    // for each keys in first result
                    foreach ($userResult[0] as $key => $var) {

                        // if it's not a number
                        if(!is_int($key) && !is_numeric($key))
                            $table .= "<th>".$key."</th>";
                    }
                    $table .= "</tr>";

                    // fill table
                    // for each line in results
                    foreach ($userResult as $line) {
                        $table .= "<tr>";

                        // for each case in line
                        foreach ($line as $key => $case) {

                            // if it's not a number
                            if(!is_int($key) && !is_numeric($key))
                                $table .= "<td>".$case."</td>";
                        }
                        $table .= "</tr>";
                    }

                    //end table
                    $table .= "</table>";
                }

                echo(json_encode(array('win'=>$win, 'points'=>$points, 'table'=>$table)));
                break;
            }
            default:{ // if urlFolder is not recognized
                throw new HTTPSpecialCaseException(404);
            }
        }
    }
}
