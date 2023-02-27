<?php

require 'Kernel/Constants.php';

final class AutoLoad {

    const AUTOLOAD_FOLDERS = array("/Kernel/", "/Controllers/", "/Utils/", "/Exceptions/");

    public static function loadClass(string $className){

        foreach(AutoLoad::AUTOLOAD_FOLDERS as $S_folder){

            $S_filepath = Constants::rootDir().$S_folder.$className.".php";
            if (is_readable($S_filepath)) {
                require $S_filepath;
            }

        }
    }
}

spl_autoload_register("AutoLoad::loadClass");
