<?php
date_default_timezone_set('America/Phoenix');
ini_set('memory_limit', '2048M');
ini_set("default_socket_timeout", 6000);
ini_set('max_execution_time', 900);
ini_set('session.gc_maxlifetime', 80000);
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
header("Access-Control-Allow-Origin: *");
// Version
define('VERSION', '3.0.2.0');
if(substr($_SERVER['HTTP_HOST'], 0, 4) == 'dev.'){
	if (is_file('config-dev.php')) {
		require_once('config-dev.php');
		// Startup
		require_once(DIR_SYSTEM . 'startup.php');
		$cache = new Cache(CACHE_DRIVER);
	}
}
elseif(substr($_SERVER['HTTP_HOST'], 0, 6) == 'devjlo'){
	if (is_file('config-dev.php')) {
		require_once('config-devjlo.php');
		// Startup
		require_once(DIR_SYSTEM . 'startup.php');
		$cache = new Cache(CACHE_DRIVER);
	}
}
elseif(substr($_SERVER['HTTP_HOST'], 0, 6) == 'devjle'){
	if (is_file('config-dev.php')) {
		require_once('config-devjle.php');
		// Startup
		require_once(DIR_SYSTEM . 'startup.php');
		$cache = new Cache(CACHE_DRIVER);
	}
}
elseif(substr($_SERVER['HTTP_HOST'], 0, 6) == 'devmre'){
	if (is_file('config-dev.php')) {
		require_once('config-devmre.php');
		// Startup
		require_once(DIR_SYSTEM . 'startup.php');
		$cache = new Cache(CACHE_DRIVER);
	}
}
else{
	if (is_file('config.php')) {
			require_once('config.php');
			// Startup
			require_once(DIR_SYSTEM . 'startup.php');
			$cache = new Cache(CACHE_DRIVER);
	}
}

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: install/index.php');
	exit;
}



start('catalog');
/*
*/
?>