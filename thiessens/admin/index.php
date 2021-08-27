<?php
// Version
define('VERSION', '3.0.3.2');

// Configuration
if(substr($_SERVER['HTTP_HOST'], 0, 3) == 'dev'){
	if (is_file('config-dev.php')) {
		require_once('config-dev.php');
	}
}
elseif($_SERVER['SERVER_NAME'] == 'localhost'){
	if (is_file('config-local.php')) {
		require_once('config-local.php');
	}
}
else{
	if (is_file('config.php')) {
		require_once('config.php');
	}
}

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: ../install/index.php');
	exit;
}

//VirtualQMOD
require_once('../vqmod/vqmod.php');
VQMod::bootup();

// VQMODDED Startup
require_once(VQMod::modCheck(DIR_SYSTEM . 'startup.php'));

start('admin');