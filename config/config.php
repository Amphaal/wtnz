<?php 

include_once "db.php";
include_once "i18n.php";


function getCurrentLibraryFileName() {
    return 'current.json';
}

function getUnifiedLibraryFileName() {
    return 'unified.json';
}

function getCurrentShoutFileName() {
    return 'shout.json';
}

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

