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

function getRootApp() {
    return "/wtnz/";
}

function getDownloadsFolder() {
    return $_SERVER["DOCUMENT_ROOT"] . '/feedtnz/downloads/';
}

////////////////
// OS Related //
////////////////

function getOS() { 

    $os_array = array(
        '/mac/i' =>  'Mac',
        '/win/i' =>  'Windows',
        '/ip/i' =>  'iOS',
        '/android/i' =>  'Android'
    );

    foreach ($os_array as $regex => $value) { 
        if (preg_match($regex, $_SERVER['HTTP_USER_AGENT'])) return $value;
    }   

    return null;
}

$_DF_OS = array(
    "osx" => "Mac",
    "win" => "Windows"
);

$_OS_DF = array_flip($_DF_OS);

function fromDownloadFolderToOS($folder) {
    global $_DF_OS;
    return array_key_exists($folder, $_DF_OS) ? $_DF_OS[$folder] : null;
}

function fromOSToDownloadFolder($os) {
    global $_OS_DF;
    return array_key_exists($os, $_OS_DF) ? $_OS_DF[$os] : null;
}

////////////////////
// END OS Related //
////////////////////

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

