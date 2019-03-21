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

    function routerLibrary($user_qs) {

        $expectedLibrary = getInternalUserFolder($user_qs) . getCurrentLibraryFileName();
        $expectedProfilePic = getPublicUserFolder($user_qs) . getProfilePicture($user_qs);

        $clientURLUnified = getPublicUserFolder($user_qs) . getUnifiedLibraryFileName();
        $clientURLShout = getPublicUserFolder($user_qs) . getCurrentShoutFileName();

        //Client variables
        $latestUpdate = filemtime($expectedLibrary);
        $isLogged = isUserLogged();

        //addons
        setTitle(i18n('libraryOf', $user_qs));
        $initialRLoaderUrl = getLocation("Home", true);

        include "front/front.php";
        exit;
    }
