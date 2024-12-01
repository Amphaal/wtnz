<?php

// display errors on http response
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

//
//
//

//
define("SOURCE_PHP_ROOT", __DIR__ . '/_src');
define("PUBLIC_FILES_ROOT", __DIR__ . '/public');
define("STATE_FILES_ROOT", __DIR__ . '/_state');
define("SERVICES_SCRIPT_ROOT", SOURCE_PHP_ROOT . '/services');

//
set_include_path(SOURCE_PHP_ROOT);

//
Swoole\Runtime::enableCoroutine(SWOOLE_HOOK_ALL); // Enable all coroutine hooks before starting any server

//
include SERVICES_SCRIPT_ROOT . '/_config.php'; 
include SERVICES_SCRIPT_ROOT . '/www.php'; 
include SERVICES_SCRIPT_ROOT . '/ws.php';