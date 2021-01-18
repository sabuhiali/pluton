<?php

$user_helpers = include CONFIG . 'helpers.php';

foreach($user_helpers as $helper) {

    $file = HELPER . $helper . '.php';

    if(file_exists($file)) {

        include $file;
    }
}