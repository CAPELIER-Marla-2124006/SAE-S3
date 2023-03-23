<?php

class RootController extends AController {

    /**
	 * Process the request by using the url (used to call the right controller)
     * @return void
	 */
    public function process(){
        switch($this->urlFolder){
            case '':
            case 'game':
            case 'index.php':{ // send to the view of the game
				$game = new GameController();
				$game->display();
                break;
            }
			case 'account':{
				$this->callController(new AccountController());
				break;
			}
            case 'api':{ // used to get data from database and update it
                $this->callController(new APIController());
                break;
            }
            default:{ // if urlFolder is not recognized
                throw new HTTPSpecialCaseException(404);
            }

        }
    }
}
