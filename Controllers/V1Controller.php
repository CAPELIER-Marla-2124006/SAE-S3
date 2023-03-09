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
                $data = new DataAccess();
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

                $rightAnswer = $dataAdmin->getExercise($this->urlParams[0])->getAnswer();

                $userAnswer = $this->postParams['answer'];

                if($userResult = $dataExercise->getResult($userAnswer) == $dataAdmin->getResult($rightAnswer)){
                    $win = ['true'];
                    $user = $dataAdmin->getUser($_SESSION['ID']);
                    $exercice = $dataAdmin->getExercise($this->urlParams[0]);
                    $points = [$user->getPoints() + $exercice->getPoints()];

                    // create table and headers of table
                    $table = "<table><tr>";
                    // for each keys in first result
                    foreach ($userResult[0] as $key => $var) {
                        // if it's not a number
                        if(!ctype_digit($key) && !is_numeric($key)) $table += "<th>".$key."</th>";
                    }
                    $table += "</tr>";
                
                    // fill table
                    // for each line in results
                    foreach ($userResult as $line) {
                        $table += "<tr>";
                        // for each case in line
                        foreach ($line as $key => $case) {
                            // if it's not a number
                            if(!ctype_digit($key) && !is_numeric($key)) $table += "<td>".$case."</td>";
                        }
                        $table += "</tr>";
                    }
                    //end table
                    $table += "</table>";
                    
                    $win = json_encode($win);
                    echo($win);
                    $points = json_encode($points);
                    echo($points);
                    $table = json_encode($table);
                    echo($table);
                }
                else{
                    $win = ['false'];
                    $user = $dataAdmin->getUser($_SESSION['ID']);
                    $exercice = $dataAdmin->getExercise($this->urlParams[0]);
                    $points = [$user->getPoints() - intdiv($exercice->getPoints(), 5)];
                    $table = "Aucun resultat";
                    
                    $win = json_encode($win);
                    echo($win);
                    $points = json_encode($points);
                    echo($points);
                    $table = json_encode($table);
                    echo($table);
                }
                // commande romain dataaccess pour récupérer les deux réponse, et les comparer(dans le controller)
                
                /*$user = $data->getUser($_SESSION['ID']);
                $user->setPoints($this->postParams['points']);
                $user->setLevel($this->postParams['level']);*/
                break;
            }
            default:{
                throw new HTTPSpecialCaseException(404);
            }
        }
    }
}
