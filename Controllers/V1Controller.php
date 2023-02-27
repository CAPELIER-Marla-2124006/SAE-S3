<?php

class V1Controller extends AController {

    public function process() {
        switch ($this->urlFolder) {
            case 'hint':{
                break;
            }

            case 'lessons':{
                echo "Lesson";
                break;
            }

            case 'notes':{
                echo "Notes";
                break;
            }

            case 'instructions':{
                echo "Instructions";
                break;
            }
            default:{
                throw new HTTPSpecialCaseException(404);
            }
        }
    }
}
