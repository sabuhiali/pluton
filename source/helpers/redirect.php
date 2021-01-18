<?php

/**
 * Yönləndirmə funksiyası - redirect()
 * 
 * @return void
 */
if(!function_exists('redirect')) {

    function redirect(string $location = '/') : void {

        ob_start();
        header("Location: {$location}");
        exit;
    }
}