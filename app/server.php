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
include SERVICES_SCRIPT_ROOT . '/websocket.php';

//
//
//

use Swoole\WebSocket\Server;
$server = new Server("0.0.0.0", SERVICE_WWW_PORT);
$server->set([
    'enable_coroutine' => true,
]);

//
$server->on('WorkerStart', function(Server $serv, $workerId) {
    // Files which won't be reloaded
    # var_dump(get_included_files());

    // Include files from here so they can be reloaded...
    include SOURCE_PHP_ROOT . '/index.php'; // Include your standard PHP script
});

$server->on('WorkerExit', function(Server $server, int $workerId) {
    //Prevent worker exit timeout issues (similar to die/exit)
    //@see: https://openswoole.com/docs/modules/swoole-event-exit
    \Swoole\Timer::clearAll();
    \Swoole\Event::Exit();
});

//
// WWW
//

$server->on("request", "wwwService");
//$wwwPort = $server->listen(HOST_PORT_LISTENING, SERVICE_WWW_PORT, SWOOLE_SOCK_TCP);

//
// WS
// 

//
//$websocketPort = $server->listen(HOST_PORT_LISTENING, SERVICE_WEBSOCKET_PORT, SWOOLE_SOCK_TCP);
setupWebSocket($server);

// Bind to termination signals
// Swoole\Process::signal(SIGTERM, function () use ($server, &$connectionsData) {
//     ws_handleShutdown($server, $connectionsData);
// });

// Swoole\Process::signal(SIGINT, function () use ($server, &$connectionsData) {
//     ws_handleShutdown($server, $connectionsData);
// });

//
//
//

$server->start();
Swoole\Event::wait();
