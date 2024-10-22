<?php

// display errors on http response
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Create a Swoole HTTP server on 0.0.0.0:9501
$server = new Swoole\Http\Server("0.0.0.0", 9501);

$documentRoot = __DIR__ . '/app';

//
//
//

// Directory where session files will be stored
define('SESSION_DIR', __DIR__ . '../_sessions');

// Session handling function
function handleSession($request, $response) {
    // Create session directory if it doesn't exist
    if (!is_dir(SESSION_DIR)) {
        mkdir(SESSION_DIR, 0777, true);
    }

    // Generate or retrieve session ID
    $sessionId = $request->cookie['SESSIONID'] ?? bin2hex(random_bytes(16));

    // Set session ID in response cookie if it's new
    if (!isset($request->cookie['SESSIONID'])) {
        $response->cookie('SESSIONID', $sessionId, time() + 3600);  // Session expires in 1 hour
    }

    // Define the session file path based on session ID
    $sessionFile = SESSION_DIR . "/session_$sessionId.json";

    // Load session data from file if it exists, otherwise create an empty session
    $session = file_exists($sessionFile) ? json_decode(file_get_contents($sessionFile), true) : [];

    // Return session ID and session data
    return [$sessionId, $session, $sessionFile];
}

//
//
//

$server->on("request", function ($request, $response) use ($documentRoot) {
    // Use the session handler function
    [$sessionId, $session, $sessionFile] = handleSession($request, $response);

    // // Method 2: Using print_r
    // $dump = print_r($request, true);
    // $response->end("<pre>$dump</pre>");

    // // Capture the output of the standard PHP script
    ob_start();
        include $documentRoot . '/index.php'; // Include your standard PHP script
    $output = ob_get_clean();

    //
    file_put_contents($sessionFile, json_encode($session));

    // Respond with the output of the PHP script
    $response->end($output);
});

// Start the Swoole server
$server->start();
