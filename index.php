<?php

/**
 *  Pluton - MVC Framework
 *  ------------------------------------------------------
 *  @author      Sabuhi Alizada <sabuhi.alizada@yandex.ru>
 *  @source      https://github.com/sabuhiali/pluton
 *  @link        https://plutonphp.org
 *  @version     1.0.0
 *  @license     MIT
 *  @copyright   PlutonPHP © 2021
 *  ------------------------------------------------------
 *  Good codings...
 */

declare(strict_types=1);
define('MIN_PHP_VERSION', '7.2', false);

/**
 *  PHP versiya kontrolu
 *  ------------------------------------------------------
 *  Sistemdə qurulu olan PHP versiyası minimum 7.2 olacağı
 *  təqdirdə layihə işləyə bilər.
 */
if(!version_compare(PHP_VERSION, MIN_PHP_VERSION, '>=')) {

    die('<span>Mövcud PHP versiyanız: <b>' . PHP_VERSION . '</b>. Minimum <b>7.2</b> olmalıdır.</span>');
}

/**
 *  Kernel starter
 *  ------------------------------------------------------
 *  Sabitlər, sinif avtoyükləyicisi, köməkçi faylları və 
 *  rota təyinatlarının icraatçı faylını daxil edib işə 
 *  salacaq.
 */
require_once __DIR__ . '/kernel/start.php';