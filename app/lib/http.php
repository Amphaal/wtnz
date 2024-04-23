<?php

/**
 * may return empty array on "/" or "/?..."
 */
function getQueryString($request_uri = null) {
    if(!$request_uri) $request_uri = $_SERVER['REQUEST_URI'];
    $request_uri = explode('/', $request_uri); // split components on slashes
    $request_uri = array_filter($request_uri, 'strlen' ); // remove non-empty
    $request_uri = array_values($request_uri); // reapply indexes (if any removes)

    //
    if(!empty($request_uri)) {
        //
        // lets check if last URL component contains "?" params splitter, and if so, ignores params into returning array
        //

        // get last component
        $request_maybe_params_raw = $request_uri[array_key_last($request_uri)];

        // explode it so we remove params from URL
        $last_url_component = explode('?', $request_maybe_params_raw)[0]; // grab index 0; only get URL part, not params !
        
        // replace last component without params in-array
        $request_uri[array_key_last($request_uri)] = $last_url_component;
    }

    //
    return $request_uri;
}

function goToLocation($rq) {
    header("Location: " . getLocation($rq));
}

function getLocation($rq, $abs = null) {
    $r = $abs ? constant("WEB_APP_ROOT_FULLPATH") : constant("WEB_APP_ROOT");

    switch($rq) {
        //
        case "Home": {
            $r .= 'manage';
        }
        break;

        //
        case "ThisLibrary": {
            $url = null;

            //
            if(isXMLHttpRequest())  {
                $temp = getQueryString($_SERVER['HTTP_REFERER']);
                array_shift($temp); //domain removal
                $url = implode("/", $temp);
            }

            //
            $r .= "u/" . (getQueryString($url)[1] ?? "");
        }
        break;
        
        //
        case "MyLibrary": {
            $r .= "u/" . getCurrentUserLogged();
        }
        break;
    }

    return strtolower($r);
}

function isXMLHttpRequest(){
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

function forceXMLHttp($force) {
   if($force) {
    $_SERVER['HTTP_X_REQUESTED_WITH'] = "xmlhttprequest";
   } 
}