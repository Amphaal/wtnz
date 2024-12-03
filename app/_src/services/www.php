<?php

use Swoole\Http\Request;
use Swoole\Http\Response;

include SOURCE_PHP_ROOT . '/lib/session.php'; 
include SOURCE_PHP_ROOT . '/lib/context_manager.php'; 

//
function wwwService(Request $request, Response $response) {
    //
    ContextManager::set("REQUEST", $request);

    // Use the session handler function
    Session::start($response);

    /* enforce singletons initializations */
    I18nHandler::refresh();
    _AbstractUsersDatabase::refresh();

    //
    ContextManager::set("exit", function (mixed $overridedOutput = null) use ($response) {
        //
        Session::persist();
        
        //
        if (isset($overridedOutput)) {
            ob_clean();
        } else {
            $overridedOutput = ob_get_clean();
        }

        //
        $response->end($overridedOutput);
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
    ContextManager::get("exit")();
};
