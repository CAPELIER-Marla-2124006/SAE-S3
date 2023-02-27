<?php

    require_once("Kernel/AutoLoad.php");

    $url = $_GET["url"];
    unset($_GET["url"]);

    $O_ctr = new Entry($url, $_GET, $_POST);
    $O_ctr->execute();
