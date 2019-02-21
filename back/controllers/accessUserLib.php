<?php 

    function getCustomBackgroundAnimColors($user) {
        $config = getUsersConfig($user);
        if(!$config) return;
        if(!array_key_exists("customColors", $config)) return;

        $colours = $config["customColors"];
        $css = ".anim::after { background: linear-gradient(-45deg, %s, %s, %s, %s);} !important";
        $css = sprintf($css, ...$colours);
        return $css;
    }

    function routerAccessUserLib($user_qs) {

        $expectedLibrary = getPublicUserFolder($user_qs) . getCurrentLibraryFileName();
        $expectedShout = getPublicUserFolder($user_qs) . getCurrentShoutFileName();

        //Client variables
        $clientURLLibrary = dirname($_SERVER['REQUEST_URI']) . substr($expectedLibrary, 1);
        $clientURLShout = dirname($_SERVER['REQUEST_URI']) . substr($expectedShout, 1);
        $latestUpdate = filemtime($expectedLibrary);

        //url based variables
        $root = "https://zonme.to2x.ovh/wtnz/";
        $sio_url = "wss://zonme.to2x.ovh:3000";

        include __DIR__ . "/../../front/home.php";
        exit;
    }
