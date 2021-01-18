<?php

namespace Pluton\Library;

class Session {

    /**
     *  Konstruktor
     * 
     *  Sinif çağırılarkən sessiya kontrolu edəcək.
     *  Mövcud deyilsə sessiyaya başladacaq.
     */
    public function __construct() {

        if(!isset($_SESSION)) $this->init();
    }

    /**
     *  Sessiya başladacaq.
     * 
     *  @return void
     */
    public function init() : void {

        session_start();
    }

    /**
     *  Mövcud sessiyanı və dəyərləri ləğv edəcək.
     * 
     *  @return void
     */
    public function close() : void {

        //  Dəyərləri ləğv etsin.
        session_unset();

        //  Sessiyanı ləğv etmək.
        session_destroy();
    }

    /**
     *  Sessiya dəyər təyinatları edəcək.
     * 
     *  @param $key
     *  @param $value
     *  @return void
     */
    public function set($key, $value = null) : void {

        /**
         *  Sessiya dəyərlərini massiv halında təyin etmək.
         * 
         *  İlk arqument massivdirsə!
         */
        if(is_array($key)) {

            foreach($key as $session_key => $session_value) {

                /**
                 *  Təyin olunan sessiya açar sözü string,
                 *  dəyər isə string, bool və say olduğu
                 *  təqdirdə məlumat əlavə oluna bilər.
                 */
                if(
                    is_string($session_value) || 
                    is_bool($session_value) ||
                    is_numeric($session_value)
                ) {
                    $_SESSION[$session_key] = $session_value;
                }
                else {

                    //  Xəta!
                    alert([
                        'title' => '"Session" xətası!',
                        'hint' => 'Məlumat tipləri uyğun deyil!'
                    ]);
                }
                
            }
        }else {

            if(
                is_string($value) || 
                is_bool($value) ||
                is_numeric($value)
            ) {
                $_SESSION[$key] = $value;
            }
            else {

                //  Xəta!
                alert([
                    'title' => '"Session" xətası!',
                    'hint' => 'Məlumat tipləri uyğun deyil!'
                ]);
            }
        }
    }

    /**
     *  Mövcudluq dəyərini alacaq.
     * 
     *  @param string $key
     *  @return bool
     */
    public function isset(string $key) : bool {

        $key = (string) $key;
        if(isset($_SESSION[$key])) return true;
        else return false;
    }

    /**
     *  Sessiya dəyərini ləğv edəcək.
     * 
     *  @param string $key
     *  @return void
     */
    public function unset(string $key) : void {

        $key = (string) $key;
        if($this->isset($key) === true) unset($_SESSION[$key]);
    }

    /**
     *  Açar sözə görə dəyər döndürəcək.
     * 
     *  @param string $key
     */
    public function get(string $key) {

        $key = (string) $key;
        if($this->isset($key)) return $_SESSION[$key]; 
    }
}