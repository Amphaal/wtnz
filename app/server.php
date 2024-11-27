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

//
include SOURCE_PHP_ROOT . '/lib/session.php'; 
include SOURCE_PHP_ROOT . '/lib/context_manager.php'; 

//
set_include_path(SOURCE_PHP_ROOT);

//
//
//

Swoole\Runtime::enableCoroutine(SWOOLE_HOOK_ALL); // Enable all coroutine hooks before starting a server
$server = new Swoole\Http\Server("0.0.0.0", 9501); // Create a Swoole HTTP server on 0.0.0.0:9501
$server->set([
    'enable_coroutine' => true,
]);

//
$server->on('WorkerStart', function($serv, $workerId) {
    // Files which won't be reloaded
    # var_dump(get_included_files());

    // Include files from here so they can be reloaded...
    include SOURCE_PHP_ROOT . '/index.php'; // Include your standard PHP script
});

//
$server->on("request", function ($request, $response) {
    //
    ContextManager::set("REQUEST", $request);

    // Use the session handler function
    Session::start($response);

    /* enforce singleton initialization */
    I18nSingleton::getInstance(true);

    //

    ContextManager::set("exit", function (mixed $msg = null) use ($response) {
        $response->end($msg);
    });
    ContextManager::set("header", function (string $header) use ($response) {
        $parts = explode(": ", $header);
        $response->header($parts[0], $parts[1]);
    });
    ContextManager::set("http_response_code", function (string $code) use ($response) {
        $response->status($code);
    });

    ContextManager::set("title", APP_NAME); // default title
    ContextManager::set("set_title", function ($superbus = null) {
        //
        $title = ContextManager::get("title", null);

        //
        if($superbus) {
            $title .=  " - " . $superbus;
            ContextManager::set("title", $title);
        }
    });

    // // Method 2: Using print_r
    // $dump = print_r(true);
    // $response->end("<pre>$dump</pre>");

    // // Capture the output of the standard PHP script
    ob_start();
        init_app();
    $output = ob_get_clean();

    //
    //
    //

    //
    Session::persist();

    // Respond with the output of the PHP script
    $response->end($output);
});

// Start the Swoole server
$server->start();
