<?php
include_once 'Db.php';
include_once 'Localization.php';

    $db = Db::getInstance();

    $result = $db->query("SHOW COLUMNS FROM translations");

    $columns = $result->fetchAll(PDO::FETCH_ASSOC);

    $possibleLanguages = array_values(array_map(function ($array) {
        return str_replace('text_', '', $array['Field']);
    }, array_filter($columns, function ($array) {
        return strpos($array['Field'], 'text_') !== false;
    })));

    if(isset($_GET['lang'])) {
        $lang = $_GET['lang'];

        if(!in_array($lang, $possibleLanguages)) {
            throw new Exception("Wrong language");
        }

        setcookie('lang', $lang);
        $_COOKIE['lang'] = $lang;
    }

    Localization::$LANG_DEFAULT = $possibleLanguages[0];

    function __($tag) {
        $lang = isset($_COOKIE['lang'])
            ? $_COOKIE['lang']
            : Localization::LANG_DEFAULT;

        $sth = Db::getInstance()->prepare("
            SELECT text_{$lang}
            FROM translations
            WHERE tag = ?;
        ");

        $data = array($tag);

        $sth->execute($data);

        $row = $sth->fetch(PDO::FETCH_NUM);

        return $row[0];
    }
?>