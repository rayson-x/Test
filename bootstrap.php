<?php
spl_autoload_register(function ($className){
    $basePath = __DIR__;

    if (class_exists($className, false) || interface_exists($className, false)) {
        return true;
    }

    $className = trim($className, '\\');

    $filename = $basePath.DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $className).'.php';

    if (!file_exists($filename)) {
        return false;
    }

    require $filename;

    return class_exists($className, false) || interface_exists($className, false);
});