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

$sourcePhpRoot = __DIR__ . '/_src';
$publicFilesRoot = __DIR__ . '/public';
include $sourcePhpRoot . '/lib/session.php'; 
include $sourcePhpRoot . '/lib/context_manager.php'; 
set_include_path($sourcePhpRoot);

//
//
//

// Create a Swoole HTTP server on 0.0.0.0:9501
$server = new Swoole\Http\Server("0.0.0.0", 9501);

$server->set([
    'enable_coroutine' => true,
]);

//
$server->on('WorkerStart', function($serv, $workerId) use ($sourcePhpRoot, $publicFilesRoot)
{
    // Files which won't be reloaded
    # var_dump(get_included_files());

    // Include files from here so they can be reloaded...
    include $sourcePhpRoot . '/index.php'; // Include your standard PHP script
});

//
$server->on("request", function ($request, $response) use ($sourcePhpRoot, $publicFilesRoot) {
    // Use the session handler function
    [$sessionFile, $session] = handleSession($sourcePhpRoot, $request, $response);

    /*
     * At the start of every new request, setup global
     * request variables using Swoole server methods.
     */
    ContextManager::set("i18nS", I18nSingleton::getInstance($sourcePhpRoot, $session, $request, true));
    ContextManager::set("i18n", generatei18n($sourcePhpRoot, $session, $request));
    ContextManager::set("injectAndDisplayIntoAdminLayout", generateAdminLayoutInjector($sourcePhpRoot, $publicFilesRoot));

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

    ContextManager::set("title", constant("APP_NAME"));
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
    // $dump = print_r($request, true);
    // $response->end("<pre>$dump</pre>");

    // // Capture the output of the standard PHP script
    ob_start();
        init_app($sourcePhpRoot, $sessionFile, $session, $request);
    $output = ob_get_clean();

    //
    //
    //

    //
    saveSession($sessionFile, $session);

    // Respond with the output of the PHP script
    $response->end($output);
});

// Start the Swoole server
$server->start();
