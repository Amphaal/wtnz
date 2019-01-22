<?php 

$users = json_decode(file_get_contents(__DIR__ . "/users.json"), true);

$config = array(
    "path_to_users_data_folder" => "./users_data/",
    "users" => $users
);

define('WTNZ_CONFIG', $config);

//get lang for i18n
$prob_loc = "en_US";
if(!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) $prob_loc = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
define('LANG', Locale::getPrimaryLanguage(Locale::acceptFromHttp($prob_loc)));
define('I18N', include( __DIR__ . "/i18n/" . LANG . ".php"));

function i18n($key, $args = NULL) {
    return sprintf(I18N[$key], $args);
}

return $config;


