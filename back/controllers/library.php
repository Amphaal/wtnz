<?php 

    function getCustomBackgroundAnimColors($user) {
        $config = getUserConfig($user);
        if(!$config) return;
        if(!array_key_exists("customColors", $config)) return;

        $colours = $config["customColors"];
        $css = ".anim::after { background: linear-gradient(-45deg, %s, %s, %s, %s);} !important";
        $css = sprintf($css, ...$colours);
        return $css;
    }

    function getProfilePicture($user) {
        $config = getUserConfig($user);
        if(!$config) return;
        if(!array_key_exists("profilePic", $config)) return;

        $profilePicPath = $config["profilePic"];
        return getPublicUserFolder($user) . $profilePicPath;
    }

    function routerLibrary($user_qs) {

        $expectedLibrary = getPublicUserFolder($user_qs) . getCurrentLibraryFileName();
        $expectedUnified = getPublicUserFolder($user_qs) . getUnifiedLibraryFileName();
        $expectedShout = getPublicUserFolder($user_qs) . getCurrentShoutFileName();
        $expectedProfilePic = getProfilePicture($user_qs);
        

        //Client variables
        $clientURLUnified = dirname($_SERVER['REQUEST_URI']) . substr($expectedUnified, 1);
        $clientURLLibrary = dirname($_SERVER['REQUEST_URI']) . substr($expectedLibrary, 1);
        $clientURLShout = dirname($_SERVER['REQUEST_URI']) . substr($expectedShout, 1);
        $latestUpdate = filemtime($expectedLibrary);

        //url based variables
        $root = "https://zonme.to2x.ovh/wtnz/";
        $sio_url = "wss://zonme.to2x.ovh:3000";

        include "front/front.php";
        exit;
    }
