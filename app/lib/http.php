<?php

function getQueryString($request_uri = null) {
    if(!$request_uri) $request_uri = $_SERVER['REQUEST_URI'];
    $request_uri = explode('/', $request_uri);
    $request_uri = array_filter($request_uri, 'strlen' );
    return $request_uri;
}

function goToLocation($rq) {
    header('Location: ' . getLocation($rq));
}

function getLocation($rq, $abs = null) {
    $r = $abs ? constant("WEB_APP_ROOT_FULLPATH") : constant("WEB_APP_ROOT");

    switch($rq) {
        case "Home":
            $r .= 'manage';
            break;
        case "ThisLibrary":
            $url = null;
            if(isXMLHttpRequest())  {
                $temp = getQueryString($_SERVER['HTTP_REFERER']);
                array_shift($temp); //domain removal
                $url = implode("/", $temp);
            }
            $r .= (getQueryString($url)[0] ?? "");
            break;
        case "MyLibrary":
            $r .= getCurrentUserLogged();
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