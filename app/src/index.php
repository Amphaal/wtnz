<?php

include $documentRoot . "/config.php";

include $documentRoot . "/lib/i18n.php";
include $documentRoot . "/lib/users-management/users_management.php";
include $documentRoot . "/lib/web_title.php";
include $documentRoot . "/lib/web_user-agent.php";
include $documentRoot . "/lib/css_compiler.php";
include $documentRoot . "/lib/string_extensions.php";
include $documentRoot . "/lib/error_handling.php";
include $documentRoot . "/lib/templating.php";
include $documentRoot . "/lib/templating.shards.php";
include $documentRoot . "/lib/file_uploading.php";
include $documentRoot . "/lib/http.php";
include $documentRoot . "/lib/magnifik_input.php";

include $documentRoot . "/controllers/uploadMusicLibrary.php";
include $documentRoot . "/controllers/uploadShout.php";
include $documentRoot . "/controllers/manage.php";
include $documentRoot . "/controllers/downloadApp.php";
include $documentRoot . "/controllers/musicLibrary.php";

// handles users sessions, start
// session_start();

function init_app($sessionFile, $request) {
    // 
    checkUserSpecificFolders($request); // generate folders if non existing
    sanitizePOST($request); // cleanup POST

    // get URI elements
    $qs = getQueryString($request);

    // 1st part of URL
    $qs_domain = array_shift($qs);
    
    //
    switch($qs_domain) {
        // should be handled by proxy (database files)
        // case 'data' : {}

        // should be handled by proxy (WebServices)
        // case 'sentry': {}

        case 'manage': {
            $qs_action = array_shift($qs); // 2nd part of URL
            return routerInterceptor_Manage($qs_action, $sessionFile, $request);
        }
        break;

        case 'download': {
            $qs_action = array_shift($qs); // 2nd part of URL
            return routerInterceptor_Download($request, $qs_action);
        }
        break;

        case 'u': {
            // 2cnd part of URL
            $qs_user =  array_shift($qs);
            if (!empty($qs_user)) $qs_user = strtolower($qs_user); // always lower

            // 
            checkUserExists($request, $qs_user); 

            // 3rd part of URL
            $qs_action = array_shift($qs);

            //
            switch($qs_action) {
                case 'uploadShout': {
                    return routerInterceptor_uploadShout($request, $qs_user);
                }
                break;

                case 'uploadMusicLibrary':
                default: {
                    // if user has no library
                    routerMiddleware_UploadMusicLibrary($request, $qs_user, $qs_action == 'uploadMusicLibrary');

                    // if action provided, but unknown, redirect to admin home
                    if(!empty($qs_action)) {
                        home();
                    } else {
                        // else, show music library
                        routerInterceptor_MusicLibrary($request, $qs_user);
                    }
                }
            }
        }
        break;

        // means root "/"
        case NULL: {
            // get users so we can display them
            $users = UserDb::all();
            setTitle($i18n("welcome"));
            $injectAndDisplayIntoAdminLayout("layout/admin/components/welcome.php", get_defined_vars());
        }

        default: {
            /** */
        }
        break;
    }

    // will default to 404 not found
    http_response_code(404);
}
