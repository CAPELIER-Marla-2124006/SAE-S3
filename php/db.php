<?php
    // Variables
    $HOST="localhost";
    $USER="IUT-SAE-USER";
    $PASS="iut-sae-user";


    /**
     * Connect to the database
     * @param string $db_name The name of the database
     * @return PDO The connection to the database
     */
    function connectDB(string $db_name)
    {
        global $HOST, $USER, $PASS;
        $db = new PDO("mysql:host=" . $HOST . ";dbname=" . $db_name, $USER, $PASS);
        // Display errors when occurs
        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        return $db;
    }
?>
