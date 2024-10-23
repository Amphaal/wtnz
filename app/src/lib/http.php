<?php

/**
 * may return empty array on "/" or "/?..."
 */
function getQueryString($request, $request_uri = null) {
    if(!$request_uri) $request_uri = $request->server['request_uri'];
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

function goToLocation($request, $rq) {
    header("Location: " . getLocation($request, $rq));
}

function getLocation($request, $rq, $abs = null) {
    $r = $abs ? getWebAppRootFullpath($request) : constant("WEB_APP_ROOT");

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
            if(isXMLHttpRequest($request))  {
                $temp = getQueryString($request->header['referer']);
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

function isXMLHttpRequest($request){
    return isset($request->header['x-requested-with']) && strtolower($request->header['x-requested-with']) === 'xmlhttprequest';
}

function forceXMLHttp($request, $force) {
   if($force) {
    $request->header['x-requested-with'] = "xmlhttprequest";
   } 
}