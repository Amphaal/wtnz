<?php 

class I18nHandler {
    private static $_default_lang = "en";
    private static $_handled_langs = ['en', 'fr'];

    private $_lang = null;
    private $_dict = null;

    private static function _dictFromLang($sourcePhpRoot, $lang) {
        $trFile = $sourcePhpRoot . "/translations/" . $lang . ".php";
        return include($trFile);
    }

    private static function _deduceUsedLanguage(mixed &$session, mixed &$request) {
        //
        $session_lang = $session['lang'] ?? null;
        
        //
        $cli_lang = Locale::getPrimaryLanguage($request->header['accept-language']) ?? null;

        //
        $requested_lang = $session_lang ?? $cli_lang;

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

    public function __construct(string &$sourcePhpRoot, mixed &$session, mixed &$request) {
        $this->_lang = self::_deduceUsedLanguage($session, $request);
        self::defineSessionLang($session, $this->_lang);
        $this->_dict = self::_dictFromLang($sourcePhpRoot, $this->_lang);
    }

    public static function defineSessionLang(mixed &$session, string &$lang) {
        $session['lang'] = $lang;
    }

};

class I18nSingleton {
    private static $_instance = null;

    public static function getInstance(string &$sourcePhpRoot, mixed &$session, mixed &$request) {

        if(is_null(self::$_instance)) {
            self::$_instance = new I18nHandler($sourcePhpRoot, $session, $request);
        }

        return self::$_instance;
    }
};

function generatei18n(string &$sourcePhpRoot, mixed &$session, mixed &$request) {
    return function ($key, ...$args) use (&$sourcePhpRoot, $session, &$request) {
        return sprintf(
            I18nSingleton::getInstance($sourcePhpRoot, $session, $request)->getDictionary()[$key], 
            ...$args
        );
    };
}
