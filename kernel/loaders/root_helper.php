<?php

$root_helpers = include SYS_CONFIG . 'helpers.php';

foreach($root_helpers as $root_helper) {

    $helper_file_dir = SYS_HELPER . $root_helper . '.php';
    if(file_exists($helper_file_dir)) {

        include $helper_file_dir;
    }
}