<?php 

include_once "config/_parts/abs_path.php";
include_once "config/_parts/db.php";
include_once "config/_parts/i18n.php";

class Config {

    private static $_instance = null;
    protected $_config;
    
    private function __construct() {  
        $this->_config["users"] = ObtainUsersDatabaseFileAsObject();
    }

    public static function forceUpdate() {
        self::$_instance = new self();
    }

    public static function get() {

        if(is_null(self::$_instance)) {
            self::forceUpdate();
        }

        return self::$_instance->_config;
    }

};

?>