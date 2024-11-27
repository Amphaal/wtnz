<?php

//
//
//

//
class _AbstractUsersDatabase {

    ///////////////
    // SINGLETON //
    ///////////////

    // database instance
    private static $_instance = null;

    // refreshes database instance
    public static function refresh(string $sourcePhpRoot) {
        self::$_instance = new self($sourcePhpRoot);
    }

    // get database
    public static function get($sourcePhpRoot) {
        //
        if(is_null(self::$_instance)) {
            self::refresh($sourcePhpRoot);
        }

        //
        return self::$_instance;
    }  

    ///////////////////
    // END SINGLETON //
    ///////////////////

    // database as JSON file
    public $_db;

    // where to search for database file (local filesystem URI)
    protected $_db_path;
    
    private function __construct(string $sourcePhpRoot) {
        $this->_db_path = getUserDbFilePath($sourcePhpRoot);
        $this->_db = $this->_obtainDatabaseAsObject();
    }

    private function _updateDbRaw($new_db_raw) {
        $dirToCreate = dirname($this->_db_path);
        if (!is_dir($dirToCreate)) {
            mkdir($dirToCreate); // create the containing directory
        }
        file_put_contents($this->_db_path, $new_db_raw); // write the new file
    }

    /**
     * @param db_to_write database as PHP object
     */
    private function _updateDb($db_to_write) {
        $db_as_json = json_encode($db_to_write, JSON_PRETTY_PRINT);
        $this->_updateDbRaw($db_as_json);
    }

    public function updateDb($new_db, string $sourcePhpRoot) {
        $this->_updateDb($new_db);
        self::refresh($sourcePhpRoot);
    }

    private function _createDefaultDatabase() {
        $this->_updateDbRaw("{}");
    }

    private function _obtainDatabaseAsObject() {
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

//
//
//

//
class UserDb {
    
    private static $_private_fields = array(
        "password" => null, 
        "email" => null
    );

    private static function _stripPrivate($data) {
        if(!$data) return $data;
        return array_diff_key($data, self::$_private_fields);
    }

    public static function update(string $sourcePhpRoot, $new_data, $targetUser = null) {
        if($targetUser == null) $targetUser = getCurrentUserLogged($sourcePhpRoot);
        if(!$targetUser) return;
        
        $allUsers = self::all($sourcePhpRoot);
    
        //from base data
        $base = array_key_exists($targetUser, $allUsers) ? $allUsers[$targetUser] : array();
        $new_data = array_merge($base, $new_data); //merge
    
        //apply
        $allUsers[$targetUser] = $new_data;
        _AbstractUsersDatabase::get($sourcePhpRoot)->updateDb($allUsers);
    }
    
    /** list all users */
    public static function all(string $sourcePhpRoot) {
        return _AbstractUsersDatabase::get($sourcePhpRoot)->_db ?? [];
    }

    public static function from(string $sourcePhpRoot, $user) {
        $requested = self::all($sourcePhpRoot)[$user] ?? null;
        if($requested) {
            if(!array_key_exists("customColors", $requested)) 
                $requested["customColors"] = constant("DEFAULT_BACKGROUND_COLORS");
        }
        return $requested;
    }

    public static function mine(string $sourcePhpRoot) {
       if(isUserLogged($sourcePhpRoot)) {
            return self::from(
                $sourcePhpRoot,
                getCurrentUserLogged($sourcePhpRoot)
            );
        }
    }

    ///
    ///
    ///

    public static function fromProtected(string $sourcePhpRoot, $user) {
        return self::_stripPrivate(
            self::from($sourcePhpRoot, $user)
        );
    }

    public static function mineProtected(string $sourcePhpRoot) {
        return self::_stripPrivate(
            self::mine($sourcePhpRoot)
        );
    }
    
};

