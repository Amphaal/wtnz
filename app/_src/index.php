<?php

include SOURCE_PHP_ROOT . "/lib/i18n.php";
include SOURCE_PHP_ROOT . "/lib/users-management/users_management.php";
include SOURCE_PHP_ROOT . "/lib/web_user-agent.php";
include SOURCE_PHP_ROOT . "/lib/css_compiler.php";
include SOURCE_PHP_ROOT . "/lib/string_extensions.php";
include SOURCE_PHP_ROOT . "/lib/error_handling.php";
include SOURCE_PHP_ROOT . "/lib/templating.php";
include SOURCE_PHP_ROOT . "/lib/templating.shards.php";
include SOURCE_PHP_ROOT . "/lib/file_uploading.php";
include SOURCE_PHP_ROOT . "/lib/http.php";
include SOURCE_PHP_ROOT . "/lib/magnifik_input.php";

include SOURCE_PHP_ROOT . "/controllers/uploadMusicLibrary.php";
include SOURCE_PHP_ROOT . "/controllers/uploadShout.php";
include SOURCE_PHP_ROOT . "/controllers/manage.php";
include SOURCE_PHP_ROOT . "/controllers/downloadApp.php";
include SOURCE_PHP_ROOT . "/controllers/musicLibrary.php";

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
        // should be handled by nginx proxy, then forwarded to WS Swoole instance
        // case WEBSOCKET_QUERY_STUB: {}

        case URI_RESOURCES_QUERY_ROOT: {
            $qs_action = array_shift($qs); // 2nd part of URL
            switch ($qs_action) {
                // should be handled by proxy (database files)
                // case URI_RESOURCES_QUERY_REPO_CHUNK : {}

                case PUBLIC_PHP_FOLDER_NAME: {
                    //
                    $wantedPublicPhpResource = PUBLIC_PHP_FOLDER_NAME . "/" . implode("/", $qs);

                    //
                    if (file_exists(SOURCE_PHP_ROOT . '/' . $wantedPublicPhpResource)) {
                        //
                        $ctMap = [
                            '.css.' => 'text/css',
                            '.js.' => 'text/javascript'
                        ];
                    
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
                }
                break;
            }
        }
        break;

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

        case 'info': {
            if (EXPOSED_HOST == "localhost") {
                ContextManager::get("header")('Content-Type: application/json');
                var_dump(get_defined_constants());
                return;
            }
        }

        case 'changeLang': {
            // only POST allowed
            $request = ContextManager::get("REQUEST");
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
            Session::setLang($lang);

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
            checkUserExists($qs_user, false); 

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

                    return;
                }
            }
        }
        break;

        // means root "/"
        case NULL: {
            // get users so we can display them
            $users = UserDb::all();
            ContextManager::get("set_title")(i18n("welcome"));
            injectAndDisplayIntoAdminLayout("layout/admin/components/welcome.php", get_defined_vars());
            return;
        }
    }

    // will default to 404 not found
    ContextManager::get("http_response_code")(404);
}
