<?php

use Swoole\WebSocket\Server;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;

// Store connections with metadata
$connectionsData = [];

//
function setupWebSocket(Swoole\WebSocket\Server &$server) {
    // $port->on('Handshake', function (Request $request, Response $response) {
        
    // });
    
    // Handle new WebSocket connections
    $server->on('Open', function (Server $server, Request $request) use (&$connectionsData) {
        $fd = $request->fd;
        echo "Connection opened: {$fd}\n";

        // Initialize connection metadata
        $connectionsData[$fd] = [
            'last_heartbeat' => time(),
        ];
    });

    // Handle incoming WebSocket messages
    $server->on('Message', function (Server $server, Frame $frame) use (&$connectionsData) {
        $fd = $frame->fd;
        $data = $frame->data;

        echo "Message received from {$fd}: {$data}\n";

        // Update heartbeat on message
        $connectionsData[$fd]['last_heartbeat'] = time();

        // Echo the message back
        $server->push($fd, "Server: {$data}");
    });

    // Handle WebSocket disconnection
    $server->on('Close', function (Server $server, $fd) use (&$connectionsData) {
        // Cleanup connection metadata
        if (isset($connectionsData[$fd])) {
            echo "Connection closed: {$fd}\n";
            unset($connectionsData[$fd]);
        }
    });

    // Periodic heartbeat check
    Swoole\Timer::tick(5000, function () use (&$server, &$connectionsData) {
        $currentTime = time();

        foreach ($connectionsData as $fd => $meta) {
            // Check if the connection is stale
            if ($currentTime - $meta['last_heartbeat'] > 10) {
                echo "Connection {$fd} is stale. Closing...\n";
                $server->close($fd);
            } else {
                // Send a ping message to check the connection
                $server->push($fd, json_encode(['type' => 'ping']));
            }
        }
    });
}

// Graceful shutdown handling
ContextManager::set("shuttingDown", false);
function ws_handleShutdown(Server $server, &$connectionsData) {
    //
    if (ContextManager::get("shuttingDown") === true) return;
    ContextManager::set("shuttingDown", true);

    echo "Shutting down server...\n";

    foreach ($connectionsData as $fd => $meta) {
        if ($server->exist($fd)) {
            echo "Closing connection {$fd}...\n";
            $server->close($fd);
        }
    }

    echo "All connections closed. Server shutdown complete.\n";
}
