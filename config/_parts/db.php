<?php

class AppDb {

    ///////////////
    // SINGLETON //
    ///////////////

    private static $_instance = null;

    public static function refresh() {
        self::$_instance = new self();
    }

    public static function get() {

        if(is_null(self::$_instance)) {
            self::refresh();
        }

        return self::$_instance;
    }  

    ///////////////////
    // END SINGLETON //
    ///////////////////

    public $_db;
    protected $_db_path;
    
    private function __construct() {
        $this->_db_path = getAppDbPath();
        $this->_db = $this->_ObtainDatabaseAsObject();
    }

    private function _updateDb($new_db) {
        @mkdir(dirname($this->_db_path)); //create the containing directory
        $new_file = json_encode($new_db, JSON_PRETTY_PRINT);
        file_put_contents($this->_db_path, $new_file); //write the new file
    }

    public function updateDb($new_db) {
        $this->_updateDb($new_db);
        self::refresh();
    }

    private function _createDefaultDatabase() {
        $this->_updateDb(
            json_decode('{}', true)
        );
    }

    private function _ObtainDatabaseAsObject() {
        $db = null;
        $file_content = @file_get_contents($this->_db_path);

        //generate the db file if inexistant
        if($file_content === FALSE) {
            $this->_createDefaultDatabase();
        } else {
            $db = json_decode($file_content, true);
        }

        if(!$db) {
            $this->_createDefaultDatabase();
        }

        return $db;
    }
    
};

class UserDb {
    
    private static $_private_fields = array(
        "password" => null, 
        "email" => null
    );

    private static function _stripPrivate($data) {
        if(!$data) return $data;
        return array_diff_key($data, self::$_private_fields);
    }

    public static function update($new_data, $targetUser = null) {
        if($targetUser == null) $targetUser = getCurrentUserLogged();
        if(!$targetUser) return;
        
        $allUsers = self::all();
    
        //from base data
        $base = array_key_exists($targetUser, $allUsers) ? $allUsers[$targetUser] : array();
        $new_data = array_merge($base, $new_data); //merge
    
        //apply
        $allUsers[$targetUser] = $new_data;
        AppDb::get()->updateDb($allUsers);
    }
    

    public static function all() {
        return AppDb::get()->_db ?? null;
    }

    public static function from($user) {
        $requested = self::all()[$user] ?? null;
        if($requested) {
            if(!array_key_exists("customColors", $requested)) 
                $requested["customColors"] = getDefaultBackgroundColors();
        }
        return $requested;
    }

    public static function mine() {
       if(isUserLogged()) {
            return self::from(getCurrentUserLogged());
        }
    }

    ///
    ///
    ///

    public static function fromProtected($user) {
        return self::_stripPrivate(self::from($user));
    }

    public static function mineProtected() {
        return self::_stripPrivate(self::mine());
    }
    
};

