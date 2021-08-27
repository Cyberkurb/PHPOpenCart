<?php
$root = '/var/www/html/thiessens'; 
// HTTP
define('HTTP_SERVER', 'https://thiessens.com/');

// HTTPS
define('HTTPS_SERVER', 'https://thiessens.com/');

// DIR
define('DIR_APPLICATION', $root.'/catalog/');
define('DIR_SYSTEM', $root.'/system/');
define('DIR_IMAGE', $root.'/image/');
define('DIR_STORAGE', DIR_SYSTEM . 'storage/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/theme/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_CACHE', DIR_STORAGE . 'cache/');
define('DIR_DOWNLOAD', DIR_STORAGE . 'download/');
define('DIR_LOGS', DIR_STORAGE . 'logs/');
define('DIR_MODIFICATION', DIR_STORAGE . 'modification/');
define('DIR_SESSION', DIR_STORAGE . 'session/');
define('DIR_UPLOAD', DIR_STORAGE . 'upload/');


// DB CW
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'dansons-cluster-1.cluster-cjocz1mn6ubo.us-west-2.rds.amazonaws.com');
define('DB_USERNAME', 'thiessens');
define('DB_PASSWORD', 'T&fcwaq+Fg4Tf#x9');
define('DB_DATABASE', 'thiessens_dev');
define('DB_PORT', '3306');
define('DB_PREFIX', 'oc_');

// DB RCI
// define('DB_DRIVER', 'mysqli');
// define('DB_HOSTNAME', 'localhost');
// define('DB_USERNAME', 'muyvxucgmp');
// define('DB_PASSWORD', 'U8nx4WhJkt');
// define('DB_DATABASE', 'muyvxucgmp');
// define('DB_PORT', '3306');
// define('DB_PREFIX', 'oc_');