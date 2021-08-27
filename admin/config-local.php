<?php

$file_location ='/Applications/MAMP/htdocs/www/Dansons-CRM/';
//$file_location = '/www/Dansons-CRM/';

define('HTTP_SERVER', 'http://localhost/admin/');
define('HTTP_CATALOG', 'http://localhost/');
define('HTTP_IMAGE', 'https://images.pitboss-grills.com/' );
// HTTPS
define('HTTPS_SERVER', 'http://localhost/admin/');
define('HTTPS_CATALOG', 'http://localhost/');
define('HTTPS_IMAGE', 'http://dj4htnsq11to6.cloudfront.net/' );
define('DIR_APPLICATION', $file_location.'admin/');
define('DIR_SYSTEM', $file_location.'system/');
define('DIR_IMAGE', $file_location.'image/');
define('DIR_STORAGE', $file_location.'storage/');
define('DIR_CATALOG', $file_location.'catalog/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/template/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_CACHE', DIR_STORAGE . 'cache/');
define('DIR_DOWNLOAD', DIR_STORAGE . 'download/');
define('DIR_LOGS', DIR_STORAGE . 'logs/');
define('DIR_MODIFICATION', DIR_STORAGE . 'modification/');
define('DIR_SESSION', DIR_STORAGE . 'session/');
define('DIR_UPLOAD', DIR_STORAGE . 'upload/');
define('CACHE_DRIVER', 'file'); 
define('CACHE_HOSTNAME', 'dansonsweb.j0v3lm.0001.usw2.cache.amazonaws.com'); 
define('CACHE_PORT', '11211'); 
define('CACHE_PREFIX', 'oc_test');
// DB
define('DB_DRIVER', 'mysqli');
    define('DB_HOSTNAME', 'localhost');
    define('DB_HOSTNAME_READ', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', 'root');
    define('DB_DATABASE', 'dansonsdb');
    define('DB_PORT', '3306');
    define('DB_PREFIX', 'oc_');

// OpenCart API
define('OPENCART_SERVER', 'https://www.opencart.com/');