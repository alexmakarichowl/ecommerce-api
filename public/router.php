<?php
/**
 * Router script for PHP built-in server.
 * Usage: php -S localhost:8000 -t public public/router.php
 * Routes all requests through index.php.
 */
$_SERVER['SCRIPT_NAME'] = '/index.php';
require __DIR__ . '/index.php';
