<?php
    // Variables
    $HOST="sae.kap.wtf";
    $DB_NAME="test";
    $USER="IUT-SAE-USER";
    $PASS="iut-sae-user";

    // Connect to DB
    $db = new PDO("mysql:host=" . $HOST . ";dbname=" . $DB_NAME, $USER, $PASS);
    // Display errors when occurs
    $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
?>