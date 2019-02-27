<?php 

class Slugifier {

    public function __construct() {
        $this->_tr_Regex = str_split(self::$_toReplace_chars);
        $this->_tr_Regex = array_map(function($in){
            return preg_quote($in, "/");
        }, $this->_tr_Regex);
        $this->_tr_Regex = implode("|",  $this->_tr_Regex);
        $this->_tr_Regex = "/" . $this->_tr_Regex . "/";
    }

    public function __invoke($x)
    {
        return $this->slugify($x);
    }

    static private $_toReplace_chars = "·/_,:;";

    private $_tr_Regex;

    private function slugify($str) {
        $str = strtolower($str);
        $str = preg_replace("/\s+/", '-', $str);
        $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
        $str = preg_replace($this->_tr_Regex, '-', $str);
        $str = preg_replace("/&/",'-and-', $str);
        $str = preg_replace("/[^\w\-]+/",'', $str);
        $str = preg_replace("/\-\-+/", '-', $str);
        $str = preg_replace("/^-+/", '', $str);
        return $str;
    }
}