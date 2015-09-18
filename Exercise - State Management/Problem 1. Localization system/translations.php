<?php
include_once 'Localization.php';

    if(isset($_GET['lang'])) {
        $lang = $_GET['lang'];

        if($lang != Localization::LANG_EN && $lang != Localization::LANG_BG) {
            throw new Exception("Wrong language");
        }

        setcookie('lang', $lang);
        $_COOKIE['lang'] = $lang;
    }

    function __($tag) {
        $lang = isset($_COOKIE['lang'])
            ? $_COOKIE['lang']
            : Localization::LANG_DEFAULT;

        return Localization::$string[$tag][$lang];
    }
?>