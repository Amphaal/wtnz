<?php 

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
    return [$sessionFile, function () use ($sessionFile, &$session) {
        file_put_contents($sessionFile, json_encode($session));
    }];
}
