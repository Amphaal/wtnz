<?php

use Swoole\WebSocket\Server;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;

$websocketServer = new Server("0.0.0.0", SERVICE_WEBSOCKET_PORT);

// Store connections with metadata
$connections = [];

// Handle new WebSocket connections
$websocketServer->on('Open', function (Server $websocketServer, Request $request) use (&$connections) {
    $fd = $request->fd;
    echo "Connection opened: {$fd}\n";

    // Initialize connection metadata
    $connections[$fd] = [
        'last_heartbeat' => time(),
    ];
});

// Handle incoming WebSocket messages
$websocketServer->on('Message', function (Server $websocketServer, Frame $frame) use (&$connections) {
    $fd = $frame->fd;
    $data = $frame->data;

    echo "Message received from {$fd}: {$data}\n";

    // Update heartbeat on message
    $connections[$fd]['last_heartbeat'] = time();

    // Echo the message back
    $websocketServer->push($fd, "Server: {$data}");
});

// Handle WebSocket disconnection
$websocketServer->on('Close', function (Server $websocketServer, $fd) use (&$connections) {
    echo "Connection closed: {$fd}\n";

    // Cleanup connection metadata
    if (isset($connections[$fd])) {
        unset($connections[$fd]);
    }
});

// Periodic heartbeat check
Swoole\Timer::tick(5000, function () use ($websocketServer, &$connections) {
    $currentTime = time();

    foreach ($connections as $fd => $meta) {
        // Check if the connection is stale
        if ($currentTime - $meta['last_heartbeat'] > 10) {
            echo "Connection {$fd} is stale. Closing...\n";
            $websocketServer->close($fd);
        } else {
            // Send a ping message to check the connection
            $websocketServer->push($fd, json_encode(['type' => 'ping']));
        }
    }
});

// Graceful shutdown handling
$shuttingDown = false;
function handleShutdown(Server $websocketServer, &$connections) {
    if ($shuttingDown) return;
    $shuttingDown = true;

    echo "Shutting down server...\n";

    foreach ($connections as $fd => $meta) {
        if ($websocketServer->exist($fd)) {
            echo "Closing connection {$fd}...\n";
            $websocketServer->close($fd);
        }
    }

    echo "All connections closed. Server shutdown complete.\n";
    exit(0); // Exit the script
}

// Bind to termination signals
Swoole\Process::signal(SIGTERM, function () use ($websocketServer, &$connections) {
    handleShutdown($websocketServer, $connections);
});

Swoole\Process::signal(SIGINT, function () use ($websocketServer, &$connections) {
    handleShutdown($websocketServer, $connections);
});

// Start the WebSocket server
$websocketServer->start();