<?php

class RootController extends AController {

    public function process(){
        switch($this->urlFolder){
            case '':
            case 'game':
            case 'index.php':{
				$game = new GameController();
				$game->display();
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
