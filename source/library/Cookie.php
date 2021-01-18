<?php

namespace Pluton\Library;

class Cookie {

    /**
     *  İcazə verilən istiqaməti tutacaq.
     * 
     *  @var string $path
     */
    private $path;

    /**
     *  Dəyərin istifadə oluna biləcəyi domen.
     * 
     *  @var string $domain
     */
    private $domain;

    /**
     *  Protokol ancaq HTTPS olduqda dəyəri saxlayacaq.
     * 
     *  @var bool $secure
     */
    private $secure = false;

    /**
     *  JS ilə məlumatların alınma icasəzi.
     *  
     *  @var bool $httpOnly
     */
    private $httpOnly = false;

    /**
     *  İcazə verilən istiqaməti alacaq.
     * 
     *  @param string $path
     *  @return void
     */
    public function path(string $path) : void {

        $this->path = $path;
    }

    /**
     *  Dəyərin istifadə oluna biləcəyi domeni alacaq.
     * 
     *  @param string $domain
     *  @return void
     */
    public function domain(string $domain) : void {

        $this->domain = $domain;
    }

    /**
     *  Dəyərin sadəcə HTTPS protokolunda tutulması icazəsini alacaq.
     * 
     *  @param bool $access
     *  @return void
     */
    public function secure(bool $access) : void {

        $this->secure = $access;
    }

    /**
     *  Dəyərin JS ilə alınma icazəsini alacaq.
     *  
     *  @param bool $access
     *  @return void
     */
    public function httpOnly(bool $access) : void {

        $this->httpOnly = $access;
    }

    /**
     *  Cookie dəyərlərini alacaq.
     * 
     *  @param string $key
     *  @param $value
     *  @param int $time
     *  @return void
     */
    public function set(string $key, $value, int $time = null) : void {
        
        if(isset($time))
            //  Zaman təyinatı təyin olunarsa
            setcookie($key, $value, $time, $this->path, $this->domain, $this->secure, $this->httpOnly);
        else
            //  Zaman təyinatı təyin olunmazsa 1 gün yadda saxlanacaq
            setcookie($key, $value, strtotime('+1 day'), $this->path, $this->domain, $this->secure, $this->httpOnly);
    }

    /**
     *  Mövcudluq dəyərini döndürəcək.
     * 
     *  @param string $key
     *  @return bool
     */
    public function isset(string $key) : bool {

        if(isset($_COOKIE[$key])) return true;
    }

    /**
     *  Dəyəri döndürəcək.
     * 
     *  @param string $key
     */
    public function get(string $key) {

        if($this->isset($key) === true) return $_COOKIE[$key];
    }

    /**
     * 
     */
    public function delete(string $key)  {

        if($this->isset($key) === true) unset($_COOKIE[$key]);
    }
}