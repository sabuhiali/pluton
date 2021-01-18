<?php

/**
 *  Xəta bildirişi edəcək.
 * 
 *  @param array $data
 */
if(!function_exists('alert')) {

    function alert(array $data) {

        extract($data);
        echo "<h1>{$title}</h1>";
        echo "<p>{$hint}</p>";
        exit;
    }
}