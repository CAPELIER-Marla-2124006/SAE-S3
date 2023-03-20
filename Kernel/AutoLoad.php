<?php

require 'Kernel/Constants.php';

final class AutoLoad {

    const AUTOLOAD_FOLDERS = array("/Kernel/", "/Controllers/", "/Utils/", "/Exceptions/", "/Model/");

    /**
     * Load the class
     * @param string $className name of the class to load
     * @return void
     */
    public static function loadClass(string $className): void
	{

        foreach(AutoLoad::AUTOLOAD_FOLDERS as $S_folder){

            $S_filepath = Constants::rootDir().$S_folder.$className.".php";
            if (is_readable($S_filepath)) {
                require $S_filepath;
            }

        }
    }
}

// Register the autoloader
spl_autoload_register("AutoLoad::loadClass");
