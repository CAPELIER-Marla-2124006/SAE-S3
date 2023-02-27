<?php
class APIController extends AController {

    public function process() {
        switch($this->urlFolder) {
            case 'v1':{
                $this->callController(new V1Controller());
                break;
            }
            default:{
                throw new HTTPSpecialCaseException(404);
            }
        }
    }
}
