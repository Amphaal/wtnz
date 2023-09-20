<?php

// display errors on http response
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include $_SERVER["DOCUMENT_ROOT"] . "/config.php";

include $_SERVER["DOCUMENT_ROOT"] . "/lib/i18n.php";
include $_SERVER["DOCUMENT_ROOT"] . "/lib/users-management/users_management.php";
include $_SERVER["DOCUMENT_ROOT"] . "/lib/web_title.php";
include $_SERVER["DOCUMENT_ROOT"] . "/lib/web_user-agent.php";
include $_SERVER["DOCUMENT_ROOT"] . "/lib/css_compiler.php";
include $_SERVER["DOCUMENT_ROOT"] . "/lib/string_extensions.php";
include $_SERVER["DOCUMENT_ROOT"] . "/lib/error_handling.php";
include $_SERVER["DOCUMENT_ROOT"] . "/lib/templating.php";
include $_SERVER["DOCUMENT_ROOT"] . "/lib/templating.shards.php";
include $_SERVER["DOCUMENT_ROOT"] . "/lib/file_uploading.php";
include $_SERVER["DOCUMENT_ROOT"] . "/lib/http.php";
include $_SERVER["DOCUMENT_ROOT"] . "/lib/magnifik_input.php";

include $_SERVER["DOCUMENT_ROOT"] . "/controllers/uploadMusicLibrary.php";
include $_SERVER["DOCUMENT_ROOT"] . "/controllers/uploadShout.php";
include $_SERVER["DOCUMENT_ROOT"] . "/controllers/manage.php";
include $_SERVER["DOCUMENT_ROOT"] . "/controllers/downloadApp.php";
include $_SERVER["DOCUMENT_ROOT"] . "/controllers/musicLibrary.php";

// handles users sessions, start
session_start();

function init_app() {
    // 
    checkUserSpecificFolders(); // generate folders if non existing
    sanitizePOST(); // cleanup POST

    // get URI elements
    $qs = getQueryString();

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
            return routerInterceptor_Manage($qs_action);
        }
        break;

        case 'download': {
            $qs_action = array_shift($qs); // 2nd part of URL
            return routerInterceptor_Download($qs_action);
        }
        break;

        case 'u': {
            // 2cnd part of URL
            $qs_user =  array_shift($qs);
            if (!empty($qs_user)) $qs_user = strtolower($qs_user); // always lower

            // 
            checkUserExists($qs_user); 

            // 3rd part of URL
            $qs_action = array_shift($qs);

            //
            switch($qs_action) {
                case 'uploadShout': {
                    return routerInterceptor_uploadShout($qs_user);
                }
                break;

                case 'uploadMusicLibrary':
                default: {
                    // if user has no library
                    routerMiddleware_UploadMusicLibrary($qs_user, $qs_action == 'uploadMusicLibrary');

                    // if action provided, but unknown, redirect to admin home
                    if(!empty($qs_action)) {
                        home();
                    } else {
                        // else, show music library
                        routerInterceptor_MusicLibrary($qs_user);
                    }
                }
            }
        }
        break;

        // means root "/"
        case NULL: {
            // get users so we can display them
            $users = UserDb::all();
            setTitle(i18n("welcome"));
            injectAndDisplayIntoAdminLayout("layout/admin/components/welcome.php", get_defined_vars());
        }

        default: {
            /** */
        }
        break;
    }

    // will default to 404 not found
    http_response_code(404);
}

init_app();
