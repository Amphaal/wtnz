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

    private static function _deduceUsedLanguage($request) {
        
        $cli_lang = Locale::getPrimaryLanguage($request->header['accept-language']) ?? null;
        $post_lang = $request->post['set_lang'] ?? null;
        if($post_lang) unset($request->post['set_lang']);
        $session_lang = $session['lang'] ?? null;
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

    public function __construct($sourcePhpRoot, $request) {
        $this->_lang = self::_deduceUsedLanguage($request);
        $session['lang'] = $this->_lang;
        $this->_dict = self::_dictFromLang($sourcePhpRoot, $this->_lang);
    }

};

class I18nSingleton {
    private static $_instance = null;

    public static function getInstance($sourcePhpRoot, $request) {

        if(is_null(self::$_instance)) {
            self::$_instance = new I18nHandler($sourcePhpRoot, $request);
        }

        return self::$_instance;
    }
};

function generatei18n(string &$sourcePhpRoot, &$request) {
    return function ($key, ...$args) use (&$sourcePhpRoot, &$request) {
        return sprintf(
            I18nSingleton::getInstance($sourcePhpRoot, $request)->getDictionary()[$key], 
            ...$args
        );
    };
}
