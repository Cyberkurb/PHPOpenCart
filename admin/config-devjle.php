<?php
//AWS
// HTTP
define('HTTP_SERVER', 'https://devjle.pitboss-grills.com/admin/');
define('HTTP_CATALOG', 'https://devjle.pitboss-grills.com/');
define('HTTP_IMAGE', 'https://dj4htnsq11to6.cloudfront.net/' );
// HTTPS
define('HTTPS_SERVER', 'https://devjle.pitboss-grills.com/admin/');
define('HTTPS_CATALOG', 'https://devjle.pitboss-grills.com/');
define('HTTPS_IMAGE', 'https://dj4htnsq11to6.cloudfront.net/' );
// DIR
define('DIR_APPLICATION', '/var/www/html/jenny/Dansons-CRM/admin/');
define('DIR_SYSTEM', '/var/www/html/jenny/Dansons-CRM/system/');
define('DIR_IMAGE', 'https://dj4htnsq11to6.cloudfront.net/');
define('DIR_STORAGE', '/var/www/html/jenny/Dansons-CRM/storage/');
define('DIR_CATALOG', '/var/www/html/jenny/Dansons-CRM/catalog/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/template/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_CACHE', DIR_STORAGE . 'cache/');
define('DIR_DOWNLOAD', DIR_STORAGE . 'download/');
define('DIR_LOGS', DIR_STORAGE . 'logs/');
define('DIR_MODIFICATION', DIR_STORAGE . 'modification/'); 
define('DIR_SESSION', DIR_STORAGE . 'session/');
define('DIR_UPLOAD', DIR_STORAGE . 'upload/');
define('CACHE_DRIVER', 'memcached'); 
define('CACHE_HOSTNAME', 'memcached-dansons.j0v3lm.cfg.usw2.cache.amazonaws.com'); 
define('CACHE_PORT', '11211'); 
define('CACHE_PREFIX', 'oc_test');


// DB
    define('DB_DRIVER', 'mysqli');
    define('DB_HOSTNAME', 'dansons-cluster-1.cluster-cjocz1mn6ubo.us-west-2.rds.amazonaws.com');
    define('DB_HOSTNAME_READ', '');
    define('DB_USERNAME', 'dansons_websites');
    define('DB_PASSWORD', "y4P#zrvjW/q5-V%<");
    define('DB_DATABASE', 'website_dev_jenny');
    define('DB_PORT', '3306');
    define('DB_PREFIX', 'oc_');

// OpenCart API
define('OPENCART_SERVER', 'https://www.opencart.com/');