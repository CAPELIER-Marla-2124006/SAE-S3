<?php

final class View {

    /**
     * Show the view
     * @param string $path path of the view
     * @param array $params parameters of the view
     * @return void
     */
    public static function show (string $path, Array $params = array()): void
    {
        $S_file = Constants::viewsDir() . $path . '.php';
        $A_view = $params;

        ob_start();
        require $S_file;
        ob_end_flush();
    }
}
