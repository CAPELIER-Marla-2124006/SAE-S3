<?php
    /**
     * Check if the user is already logged in, or destroy the session if it's been to long since last connexion
     * @return bool true only if the user is connected since less than 4 hours
     */
    function isLogged(): bool
    {
        if(isset($_COOKIE["PHPSESSID"])) { // has a session cookie ?
            session_start(); // read from it
            if(isset($_SESSION["logintime"])){
                $MAX_SESSION_TIME = 4*60*60; // 4 hours, then no longer valid
                if(time() < $_SESSION["logintime"]+$MAX_SESSION_TIME){ // is timeout not reached yet ?
                    return true;
                }else{
                    // while we are at it, destroy the session
                    session_destroy();

                }
            }
        }
        return false;
    }
