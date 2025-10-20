<?php

require_once 'env.php';

function loadLanguage($lang)
{
    $langFile = __DIR__ . "/../app/languages/$lang.php";
    if (file_exists($langFile)) {
        return include $langFile;
    }
    return include __DIR__ . '/../app/languages/en.php'; // Fallback to English if the language file does not exist
}

?>
