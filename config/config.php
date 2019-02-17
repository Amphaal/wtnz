<?php 

define('USER_DB_PATH', dirname(__DIR__) . "/data/users.json");
define('USERS_DATA_RPATH', "/data/users");
define('USERS_DATA_PATH', dirname(__DIR__) . USERS_DATA_RPATH);

function updateUserDb($new_file) {
    mkdir(dirname(USER_DB_PATH));   
    $new_file = json_encode($new_file, JSON_PRETTY_PRINT);
    file_put_contents(USER_DB_PATH, $new_file);
}

function getUserDb() {
    
    $db;
    $file_content = @file_get_contents(USER_DB_PATH);
    
    if($file_content === FALSE) {
        $db = json_decode('{}', true);
        updateUserDb($db);
    }

    if(!isset($db)) {
        $db = json_decode($file_content, true);
    }

    return $db;
}




//get lang for i18n
$prob_loc = "en_US";
if(!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) $prob_loc = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
define('LANG', Locale::getPrimaryLanguage(Locale::acceptFromHttp($prob_loc)));
define('I18N', include( __DIR__ . "/i18n/" . LANG . ".php"));

function i18n($key, ...$args) {
    return sprintf(I18N[$key], ...$args);
}

class Config {

    private static $_instance = null;
    private $config;
    private function __construct() {  
        $this->config["users"] =  getUserDb();
    }

    public static function forceUpdate() {
        self::$_instance = new Config();
    }

    public static function get() {

        if(is_null(self::$_instance)) {
            self::$_instance = new Config();  
        }

        return self::$_instance;
    }

    public function config() {
        return $this->config;
    }
}


function getConfig() {
    return Config::get()->config();
}


?>

