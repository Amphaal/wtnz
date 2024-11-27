<?php

include $sourcePhpRoot . "/config.php";

include $sourcePhpRoot . "/lib/i18n.php";
include $sourcePhpRoot . "/lib/users-management/users_management.php";
include $sourcePhpRoot . "/lib/web_user-agent.php";
include $sourcePhpRoot . "/lib/css_compiler.php";
include $sourcePhpRoot . "/lib/string_extensions.php";
include $sourcePhpRoot . "/lib/error_handling.php";
include $sourcePhpRoot . "/lib/templating.php";
include $sourcePhpRoot . "/lib/templating.shards.php";
include $sourcePhpRoot . "/lib/file_uploading.php";
include $sourcePhpRoot . "/lib/http.php";
include $sourcePhpRoot . "/lib/magnifik_input.php";

include $sourcePhpRoot . "/controllers/uploadMusicLibrary.php";
include $sourcePhpRoot . "/controllers/uploadShout.php";
include $sourcePhpRoot . "/controllers/manage.php";
include $sourcePhpRoot . "/controllers/downloadApp.php";
include $sourcePhpRoot . "/controllers/musicLibrary.php";


// handles users sessions, start
// session_start();

function init_app(string $sourcePhpRoot, string $sessionFile, mixed &$session, mixed &$request) {
    // 
    checkUserSpecificFolders($sourcePhpRoot, $request); // generate folders if non existing
    sanitizePOST($request); // cleanup POST

    // get URI elements
    $qs = getQueryString($request);

    // 1st part of URL
    $qs_domain = array_shift($qs);

    //
    if($qs_domain == "public_php") {
        //
        $wantedPublicPhpResource = "public_php/" . implode("/", $qs);

        //
        if (file_exists($sourcePhpRoot . '/' . $wantedPublicPhpResource)) {
            //
            $ctMap = array(
                '.css.' => 'text/css',
                '.js.' => 'text/javascript'
            );
        
            //
            foreach ($ctMap as $ext => $contentType) {
                if (!str_contains($wantedPublicPhpResource, $ext)) continue;
                ContextManager::get("header")('Content-Type: ' . $contentType);
                break;
            }

            //
            include $wantedPublicPhpResource;
            return;
        }
    } else {
        switch($qs_domain) {
            // should be handled by proxy (database files)
            // case 'data' : {}

            // should be handled by proxy (WebServices)
            // case 'sentry': {}

            case 'manage': {
                $qs_action = array_shift($qs); // 2nd part of URL
                return routerInterceptor_Manage($qs_action, $sessionFile, $session, $sourcePhpRoot, $request);
            }
            break;

            case 'download': {
                $qs_action = array_shift($qs); // 2nd part of URL
                return routerInterceptor_Download($sourcePhpRoot, $request, $qs_action);
            }
            break;

            case 'changeLang': {
                // only POST allowed
                if (!isset($request->post)) {
                    ContextManager::get("http_response_code")(405);
                    return;
                }
                
                //
                $lang = $request->post['set_lang'];
                $redirectTo = $request->post['redirectTo'];
                if (!(isset($lang) && isset($redirectTo))) {
                    ContextManager::get("http_response_code")(500);
                    echo "missing either lang or redirectTo in payload";
                    return;
                }

                //
                I18nHandler::defineSessionLang($session, $lang);

                //
                ContextManager::get("http_response_code")(303); # https://fr.wikipedia.org/wiki/Post-redirect-get
                ContextManager::get("header")('Location: ' . $redirectTo);
                return;
            }
            break;

            case 'u': {
                // 2cnd part of URL
                $qs_user =  array_shift($qs);
                if (!empty($qs_user)) $qs_user = strtolower($qs_user); // always lower

                // 
                checkUserExists($sourcePhpRoot, $request, $qs_user); 

                // 3rd part of URL
                $qs_action = array_shift($qs);

                //
                switch($qs_action) {
                    case 'uploadShout': {
                        return routerInterceptor_uploadShout($sourcePhpRoot, $request, $qs_user);
                    }
                    break;

                    case 'uploadMusicLibrary':
                    default: {
                        // if user has no library
                        routerMiddleware_UploadMusicLibrary($sourcePhpRoot, $request, $qs_user, $qs_action == 'uploadMusicLibrary');

                        // if action provided, but unknown, redirect to admin home
                        if(!empty($qs_action)) {
                            home($sourcePhpRoot, $session, $request);
                        } else {
                            // else, show music library
                            routerInterceptor_MusicLibrary($sourcePhpRoot, $session, $request, $qs_user);
                        }

                        return;
                    }
                }
            }
            break;

            // means root "/"
            case NULL: {
                // get users so we can display them
                $users = UserDb::all($sourcePhpRoot);
                ContextManager::get("set_title")(ContextManager::get("i18n")("welcome"));
                ContextManager::get("injectAndDisplayIntoAdminLayout")("layout/admin/components/welcome.php", get_defined_vars());
                return;
            }
        }
    }

    // will default to 404 not found
    ContextManager::get("http_response_code")(404);
}
