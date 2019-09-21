<?php

/**
 * Auto load Function
 * 
*/

use core\error\FileNotExistsException as FileNotExistsException;

spl_autoload_register(function ($classPath) {
    $file_path = BASE_PATH .DIRECTORY_SEPARATOR. str_replace("\\", DIRECTORY_SEPARATOR , $classPath). '.php';
    if(file_exists($file_path)) {
        require_once "$file_path";
    } else {
        throw new FileNotExistsException("File donot exists {$file_path}");
    }
});