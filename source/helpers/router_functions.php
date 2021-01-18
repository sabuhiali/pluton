<?php

/**
 *  Təyin olunan rota istiqamətində parametrləri 
 *  əvəz edib RegEx üçün uyğun `string` dəyərini
 *  döndürəcək.
 * 
 *  @param string $data
 *  @return string
 */
if(!function_exists('convertParams')) {

    function convertParams(string $data) : string {
    
        /**
         *  Rota istiqaməti təyinatında istiadə 
         *  oluna biləcək parametrlər.
         */
        $getUriParams = [
    
            '/:alpha/',
            '/:num/',
            '/:any/'
        ];
    
        /**
         *  Təyin olunan rota istiqamətindəki 
         *  parametrləri əvəz edecək dəyərlər.
         */
        $setUriParams = [
    
            '([a-zA-Z@._-]+)',
            '([0-9._-]+)',
            '([a-zA-Z0-9@._-]+)'
        ];
    
        $data = preg_replace($getUriParams, $setUriParams, $data);
    
        $data = '/^' . str_replace('/', '\/', $data) . '$/';
    
        return $data;
    }
}