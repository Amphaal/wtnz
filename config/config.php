<?php 

$users = json_decode(file_get_contents(__DIR__ . "/users.json"), true);

$config = array(
    "path_to_users_data_folder" => "./users_data/",
    "users" => $users
);

define('WTNZ_CONFIG', $config);

//get lang for i18n
define('LANG', Locale::getPrimaryLanguage(Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']) ?? "en_US"));
define('I18N', include( __DIR__ . "/i18n/" . LANG . ".php"));

function i18n($key, $args = NULL) {
    return sprintf(I18N[$key], $args);
}

return $config;


