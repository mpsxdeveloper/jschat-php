<?php

require "environment.php";

global $config;
$config = array();
define("TITLE", "JSChat");

if(ENVIRONMENT == "development") {
    define("BASE", "http://localhost/jschat/");
    $config['dbname'] = "jschat";
    $config['dbhost'] = "localhost";
    $config['dbuser'] = "root";
    $config['dbpassword'] = "";
}
else {
    define("BASE", "");
    $config['dbname'] = "";
    $config['dbhost'] = "localhost";
    $config['dbuser'] = "root";
    $config['dbpass'] = "root";
}