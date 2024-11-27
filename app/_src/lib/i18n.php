<?php 

class I18nHandler {
    private static $_default_lang = "en";
    private static $_handled_langs = ['en', 'fr'];

    private $_lang = null;
    private $_dict = null;

    private static function _dictFromLang(string $lang) {
        $trFile = SOURCE_PHP_ROOT . "/translations/" . $lang . ".php";
        return include($trFile);
    }

    private static function _deduceUsedLanguage() {
        //
        $requested_lang = 
            Session::getLang()
            ?? Locale::getPrimaryLanguage(ContextManager::get("REQUEST")->header['accept-language']) 
            ?? null;

        if(in_array($requested_lang, self::$_handled_langs, true)) {
            return $requested_lang;
        }
     
        return self::$_default_lang;
    }

    public function getLang() {
        return $this->_lang;
    }
    
    public function getDictionary() {
        return $this->_dict;
    }

    public function __construct() {
        $this->_lang = self::_deduceUsedLanguage();
        $this->_dict = self::_dictFromLang($this->_lang);
    }
};

class I18nSingleton {
    private static $_instance = null;

    public static function getInstance(bool $force = false): I18nHandler {

        if($force || isset(self::$_instance)) {
            self::$_instance = new I18nHandler();
        }

        return self::$_instance;
    }
};

function i18n ($key, ...$args) {
    return sprintf(
        I18nSingleton::getInstance()->getDictionary()[$key], 
        ...$args
    );
};
