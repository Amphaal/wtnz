<?php 

class I18nHandler {
    private static $_default_lang = "en";
    private static $_handled_langs = ['en', 'fr'];

    private $_lang = null;
    private $_dict = null;


    private static function _dictFromLang($lang) {
        return include( __DIR__ . "/i18n/" . $lang . ".php");
    }

    private static function _deduceUsedLanguage() {
        
        $cli_lang = Locale::getPrimaryLanguage($_SERVER['HTTP_ACCEPT_LANGUAGE']) ?? null;
        $post_lang = $_POST['set_lang'] ?? null;
        $session_lang = $_SESSION['lang'] ?? null;
        $requested_lang = $post_lang ?? $session_lang ?? $cli_lang ?? self::$_default_lang;

        if(!in_array($requested_lang, self::$_handled_langs, true)) {
            $requested_lang = self::$_default_lang;
        }
     
        return $requested_lang;
    }

    public function getLang() {
        return $this->_lang;
    }
    
    public function getDictionary() {
        return $this->_dict;
    }

    public function __construct() {
        $this->_lang = self::_deduceUsedLanguage();
        $_SESSION['lang'] = $this->_lang;
        $this->_dict = self::_dictFromLang($this->_lang);
    }

};

class I18nSingleton {
    private static $_instance = null;

    public static function getInstance() {

        if(is_null(self::$_instance)) {
            self::$_instance = new I18nHandler();
        }

        return self::$_instance;
    }
};

function i18n($key, ...$args) {
    return sprintf(
        I18nSingleton::getInstance()->getDictionary()[$key], 
        ...$args
    );
}
