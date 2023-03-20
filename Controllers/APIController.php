<?php
class APIController extends AController {

    public function process() {
        switch($this->urlFolder) {
            case 'v1':{ // send to the controller of the version 1 of the API
                $this->callController(new V1Controller());
                break;
            }
            default:{ // if urlFolder is not recognized
                throw new HTTPSpecialCaseException(404);
            }
        }
    }
}
