<?php

require 'Application/Lib/Dev.php';

use Application\Core\Router;

spl_autoload_register(function ($class) {
	$path = str_replace('\\',$_ENV['PWD'],$class.'.php');
	if (file_exists($path)) {
		require $path;
	}
});

session_start();

$router = new Router();

// Include routes
require_once 'Application/config/routes.php';

$router->run();
