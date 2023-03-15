<?php

class V1Controller extends AController {

    public function process() { // TODO FIX DATAACCESS
        switch ($this->urlFolder) { 
            case 'exercise':{
                $data = new DataAccess();
                $exercice = $data->getExercise($this->urlParams[0]);//urlParams[0] = nb level
                $php_array = [
                    'lesson'=>$exercice->getLesson(),
                    'instructions'=>$exercice->getInstructions(),
                    'hint'=>$exercice->getHint(),
                    'codeInit'=>$exercice->getCodeInit(),
                    'success'=>$exercice->getSuccess()
                ];
                $json_array = json_encode($php_array);
                echo($json_array);
                break;
            }
            case 'saveUser':{
                $data = new DataAccessAdmin();
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
            case 'submit':{//points et user level
                /////// PSEUDO CODE ////////
                $dataExercise = new DataAccessUser(); // connect as not Admin
                $dataAdmin = new DataAccessAdmin(); // connect as Admin
                
                // get current connected user
                $user = $dataAdmin->getUser($_SESSION['ID']);
                
                // get exercise object of the exrcise asked
                $exerciceAsked = $dataAdmin->getExercise($this->urlParams[0]);
                // user answer send in post
                $userAnswer = $this->postParams['answer'];
                
                // get right answer of the exercise asked
                $rightAnswer = $dataAdmin->getExercise($this->urlParams[0])->getAnswer();
                // get result array of the user answer
                $userResult = $dataExercise->getResult($userAnswer);

                if($userResult == $dataAdmin->getResult($rightAnswer)){
                    
                    $win = true;
                    $points = $user->getPoints() + $exerciceAsked->getPoints();
                    $user->setLevel($this->postParams['level']);
                    
                } else {

                    $win = false;
                    $points = $user->getPoints() - intdiv($exerciceAsked->getPoints(), 5);

                }
                $user->setPoints($points);
                
                // create table and headers of table
                $table = "<table><tr>";
                // for each keys in first result
                foreach ($userResult[0] as $key => $var) {
                    // if it's not a number
                    if(!ctype_digit($key) && !is_numeric($key))
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
                        if(!ctype_digit($key) && !is_numeric($key))
                            $table .= "<td>".$case."</td>";
                    }
                    $table .= "</tr>";
                }
                //end table
                $table .= "</table>";

                $returnArray = [$win, $points, $table];

                echo(json_encode($returnArray));
                
                break;
            }
            default:{
                throw new HTTPSpecialCaseException(404);
            }
        }
    }
}
