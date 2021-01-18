<?php

/**
 *  Class autoloader
 * 
 *  @return void
 */
spl_autoload_register(function($class) : void {

    $class_file = classToDir($class);
    if(file_exists($class_file)) {

        include $class_file;
    }
});