<?php

    require_once("Kernel/AutoLoad.php");
	require_once("vendor/autoload.php");

	$dotenv = \Dotenv\Dotenv::createImmutable(Constants::rootDir());
	$dotenv->load();

    $url = $_GET["url"];
    unset($_GET["url"]);

    $O_ctr = new Entry($url, $_GET, $_POST);
    $O_ctr->execute();
