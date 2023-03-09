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
                    'points'=>$exercice->getPoints(),
                    'success'=>$exercice->getSuccess()
                ];
                $json_array = json_encode($php_array);
                echo($json_array);
                break;
            }
            case 'saveUser':{
                $data = new DataAccess();
                $user = $data->getUser($_SESSION['ID']);
                $user->setNotes($this->postParams['notes']);
                $user->setColorHue($this->postParams['colorHue']);
                $data->updateUser($user);
                break;
            }
            case 'submit':{//points et user level
                /////// PSEUDO CODE ////////
                $dataExercise = new DataAccessUser(); // connect as not Admin
                $dataAdmin = new DataAccessAdmin(); // connect as Admin

                $rightAnswer = $dataAdmin->getExercise($this->urlParams[0])->getAnswer();
                
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
