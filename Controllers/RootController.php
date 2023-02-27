<?php

class RootController extends AController {

    public function process(){
        switch($this->urlFolder){
            case '':
            case 'game':
            case 'index.php':{
                echo "home page";
                break;
            }
            case 'api':{
                $this->callController(new APIController());
                break;
            }
            default:{
                throw new HTTPSpecialCaseException(404);
            }

        }
    }
}
