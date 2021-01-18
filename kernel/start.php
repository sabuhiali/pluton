<?php

define('BASE_DIR',      $_SERVER['DOCUMENT_ROOT'],  false);
define('HTTP_METHOD',   $_SERVER['REQUEST_METHOD'], false);
define('HTTP_URI',      $_SERVER['REQUEST_URI'],    false);

define('DS',            DIRECTORY_SEPARATOR,        false);
define('ROOT',          BASE_DIR . DS,              false);
define('ASSET',        ROOT . 'assets' . DS,       false);
define('CONFIG',        ROOT . 'config' . DS,       false);
define('HELPER',        ROOT . 'helpers' . DS,      false);
define('HTTP',          ROOT . 'http' . DS,         false);

define('VISUAL',        ROOT . 'visualize' . DS,    false);
define('MODEL',         VISUAL . 'models' . DS,     false);
define('VIEW',          VISUAL . 'views' . DS,      false);

//  Qaynaq sabitləri
define('SOURCE',        ROOT . 'source' . DS,       false);
define('SYS_CONFIG',    SOURCE . 'config' . DS,     false);
define('SYS_HELPER',    SOURCE . 'helpers' . DS,    false);


// 
include_once 'loaders/root_helper.php';

// 
include_once 'loaders/user_helper.php';

//  sinif
include_once 'loaders/class.php';

include_once CONFIG . 'routes.php';