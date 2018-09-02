<?php 

$users = json_decode(file_get_contents(__DIR__ . "/users.json"), true);

$config = array(
    "path_to_users_data_folder" => "./users_data/",
    "users" => $users
);

define('WTNZ_CONFIG', $config);

return $config;


