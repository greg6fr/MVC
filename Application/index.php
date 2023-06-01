<?php

define("__SALT__",'DKt!9$g9fLRq#ke!bqS3NJBa3r#eHGz8MQ!d3qy9'); // dashlane

use Library\Autoloader;
use Library\Routeur;
use Library\Session;

require_once "Library/Autoloader.php";

Autoloader::register(); // psr-4
Session::start();
Routeur::dispatch();