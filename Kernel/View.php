<?php

final class View {

    public static function show (string $path, Array $params = array()){
        $S_file = Constants::viewsDir() . $path . '.php';
        $A_view = $params;

        ob_start();
        include $S_file;
        ob_end_flush();
    }
}

?>
