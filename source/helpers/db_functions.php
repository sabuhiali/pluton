<?php

/**
 *  argsToString()
 * 
 *  Nə qədər arqument alarsa hamsını
 *  `string` tipinə çevirib massiv
 *  dəyər döndürəcək.
 * 
 *  @return array
 */
if(!function_exists('argsToString')) {

    function argsToString() : array {

        $args = func_get_args();
        $converted = [];
        foreach($args as $arg) {

            $converted[] = (string) $arg;
        }
        return $converted;
    }
}

/**
 *  tableFieldParser()
 * 
 *  Aldığı massiv dəyəri SQL sorğusunda sütun(lar)
 *  üçün uyğun cümləni hazırlayacaq.
 *  
 *  @param string $data
 *  @return string
 */
if(!function_exists('tableFieldParser')) {

    function tableFieldParser(array $data) : string {

        $fields = [];
        foreach(array_keys($data) as $key) {
    
            //  Sütun adlarını massivə yerləşdirmə.
            $fields[] = "`$key`";
        }
    
        /**
         *  Alınan massivi `string` tipinə çevirmə və massiv dəyərlərinin
         *  aralığına `,` işarəsini əlavə etmə.
         *  Ən sonda da alınan dəyəri döndürmək!!!
         */
        return (string) implode(',', $fields);
    }
}

/**
 *  tableColumnParser()
 * 
 *  Aldığı massiv dəyəri SQL sorğusunda dəyər(lər)
 *  üçün uyğun cümləni hazırlayacaq
 * 
 *  @param string $data
 *  @return string
 */
if(!function_exists('tableColumnParser')) {

    function tableColumnParser(array $data) : string {

        $columns = [];
        foreach(array_values($data) as $value) {
    
            //  Dəyərlərin massivə yerləşməsi
            $columns[] = "'$value'";
        }
    
        /**
         *  Alınan massivi `string` tipinə çevirmə və massiv dəyərlərinin
         *  aralığına `,` işarəsini əlavə etmə.
         *  Ən sonda da alınan dəyəri döndürmək!!!
         */
        return (string) implode(',', $columns);
    }
}

/**
 *  tableDataMatcher()
 * 
 *  Aldığı massiv ddəyəri SQL UPDADE sorğusunda sütun 
 *  və dəyərləri uyğunlaşdırmaq üçün cümləni hazırlayacaq.
 * 
 *  @param string $data
 *  @return string
 */
if(!function_exists('tableDataMatcher')) {

    function tableDataMatcher(array $data, string $btw) : string {

        $matcher = [];
        foreach($data as $key => $value) {

            $matcher[] = "`$key` = '$value'";
        }
        return (string) implode($btw, $matcher);
    }
}