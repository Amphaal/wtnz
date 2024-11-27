<?php 

Class Session {
    /**
     * Session handling function
     */
    static function start($response) {
        $sessionDir = getSessionStorageDir();

        // Create session directory if it doesn't exist
        if (!is_dir($sessionDir)) {
            mkdir($sessionDir, 0777, true);
        }

        // Generate or retrieve session ID
        $cookies = ContextManager::get("REQUEST")->cookie;
        $sessionId = $cookies['SESSIONID'] ?? bin2hex(random_bytes(16));

        // Set session ID in response cookie if it's new
        if (!isset($cookies['SESSIONID'])) {
            $response->cookie('SESSIONID', $sessionId, time() + 3600);  // Session expires in 1 hour
        }

        // Define the session file path based on session ID
        $sessionFilePath = $sessionDir . "/session_$sessionId.json";

        // Load session data from file if it exists, otherwise create an empty session
        $session = file_exists($sessionFilePath) 
            ? json_decode(file_get_contents($sessionFilePath), true) 
            : [];

        //
        ContextManager::set("SESSION", $session);
        ContextManager::set("SESSION_FILE", $sessionFilePath);
    }

    /** */

    // save session into its file
    static function persist() {
        //
        $session = ContextManager::get("SESSION");
        $sessionFile = ContextManager::get("SESSION_FILE");

        //
        file_put_contents(
            $sessionFile, 
            json_encode($session)
        );
    }

    /**
     * 
     */
    static private function _updateItem(string $key, mixed $value) {
        $session = ContextManager::get("SESSION");
        $session[$key] = $value;
        ContextManager::set("SESSION", $session);
    }

    static private function _getItem(string $key) {
        $session = ContextManager::get("SESSION");
        return $session[$key] ?? null;
    }

    //
    //
    //

    static function setLang(string $lang) { self::_updateItem('lang', $lang); }
    static function getLang() : ?string { return self::_getItem('lang'); }

    static function setLoggedUser(string $username) { self::_updateItem('loggedAs', $username); }
    static function getLoggedUser() : string { return self::_getItem('loggedAs') ?? ""; }
}