<?php
$_SERVER["SCRIPT_URL"] = "/cron/lafameusemiseajour"; // you can set url in routes if you want
$_SERVER["HTTP_HOST"] = "mapizza.esy.es"; // without http://www
require(dirname(__FILE__) . "/../index.php");  // path to index.php

echo 'en cours...';