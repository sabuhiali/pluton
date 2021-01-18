<?php

/**
 *  Aldığı sinif adını fayl istiqamətinə çevirəcək
 * 
 *  @return string
 */
if(!function_exists('classToDir')) {

    function classToDir(string $class) : string {

        $setting = include SYS_CONFIG . 'prefix.php';
        $class = str_replace('\\', '/', $class);
        $class = preg_replace($setting['prefix'], $setting['dir'], $class) . '.php';
        return $class;
    }
}

/**
 *  Aldığı `namespace` dəyərindən sinif adını döndürəcək.
 * 
 *  @param string $namespace
 */
if(!function_exists('getClassname')) {

    function getClassname(string $namespace) {

        //  Dəyəri `/` simvoluna görə massivə döndürmə
        $namespace = explode('\\', $namespace);

        //  Massivin sonuncu üzvü
        $lastKey = array_key_last($namespace);

        return $namespace[$lastKey];
    }
}