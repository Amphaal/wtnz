<?php

// Enable all coroutine hooks before starting a server
Swoole\Runtime::enableCoroutine(SWOOLE_HOOK_ALL);

// display errors on http response
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

//
//
//

$documentRoot = __DIR__ . '/app';
include $documentRoot . '/lib/session.php'; 
include $documentRoot . '/lib/context_manager.php'; 

//
//
//

// Create a Swoole HTTP server on 0.0.0.0:9501
$server = new Swoole\Http\Server("0.0.0.0", 9501);

$server->set([
    'enable_coroutine' => true,
]);

//
$server->on('WorkerStart', function($serv, $workerId) use ($documentRoot)
{
    // Files which won't be reloaded
    var_dump(get_included_files());

    // Include files from here so they can be reloaded...
    include $documentRoot . '/index.php'; // Include your standard PHP script
});

//
$server->on("request", function ($request, $response) use ($documentRoot) {
    // Use the session handler function
    [$sessionFile, $saveSession] = handleSession($request, $response);

    //
    $exit = function ($msg = null) use (&$saveSession, &$response) {
        //
        $saveSession();
        
        $output = ob_get_clean() + !empty($msg) ? $msg : "";

        // Respond with the output of the PHP script
        $response->end($output);
    };

    /*
     * At the start of every new request, setup global
     * request variables using Swoole server methods.
     */
    ContextManager::set("i18n", generatei18n($documentRoot, $request));
    ContextManager::set("injectAndDisplayIntoAdminLayout", generateAdminLayoutInjector($documentRoot));

    //

    ContextManager::set("exit", function (&$response, $msg = null) {
        $response->exit($msg);
    });
    ContextManager::set("header", function (&$response, string &$header) {
        $parts = explode(": ", $header);
        $response->header($parts[0], $parts[1]);
    });
    ContextManager::set("http_response_code", function (&$response, string &$code) {
        $response->status($code);
    });

    // // Method 2: Using print_r
    // $dump = print_r($request, true);
    // $response->end("<pre>$dump</pre>");

    // // Capture the output of the standard PHP script
    ob_start();
        init_app($sessionFile, $request);
    $exit();
});

// Start the Swoole server
$server->start();
