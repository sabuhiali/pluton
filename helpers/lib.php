<?php

/**
 *  Kitabxana daxil edəcək.
 * 
 *  @param string $libname
 *  @return object
 */
if(!function_exists('lib')) {

    function lib(string $libname) : object {

        //  Alınan dəyərin baş hərfini böyütmək.
        $libname = ucfirst($libname);

        //  Sinfin tam adı
        $libclass = '\Pluton\Library\\' . $libname;

        //  Sinfi döndürmə.
        return new $libclass;
    }
}