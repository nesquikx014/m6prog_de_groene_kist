<?php

define('DB_HOST', getenv('DB_HOST') ?: 'mariadb');
define('DB_USER', getenv('DB_USER') ?: 'thewall_user');
define('DB_PASS', getenv('DB_PASS') ?: 'thewall_secure_pass');
define('DB_NAME', getenv('DB_NAME') ?: 'thewall_db');

define('APP_NAME', 'The Wall - Message Board');
define('APP_VERSION', '1.0.0');
define('APP_DEBUG', true);

date_default_timezone_set('Europe/Amsterdam');

if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
