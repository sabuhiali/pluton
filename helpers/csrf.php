<?php

/**
 *  Hər yeniləmədə yeni token sesiya dəyərəi döndürəcək.
 * 
 *  @return string
 */
if(!function_exists('csrf_token')) {

    function csrf_token() : string {

        $session = lib('session');
        $token = (string) bin2hex(random_bytes(32));
        
        if($session->unset('_token') !== true) $session->set('_token', $token);
        return $token;
    }
}

/**
 *  Mövcud token və uyğunluq kontrolu edəcək.
 * 
 *  @return bool
 */
if(!function_exists('csrf_check')) {

    function csrf_check() : bool {

        $session = lib('session');
        if(
            isset($_REQUEST['_token']) and 
            $_REQUEST['_token'] === $session->get('_token')
        ) return true;
    }
}