<?php
function loadConst($name)
{
    $file = __DIR__ . "/../constants/$name.php";
    if (file_exists($file)) {
        return include $file;
    }
    return null;
}
?>