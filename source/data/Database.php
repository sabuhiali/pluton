<?php

namespace Pluton\Data;

class Database {

    /**
     *  MySQL bağlantını tutacaq.
     * 
     *  @var object
     */
    private $connect;

    /**
     *  MySQL sorğunu tutacaq.
     * 
     *  @var object
     */
    protected $query;

    /**
     *  Konstruktor
     * 
     *  Sinif istifadə olunarkən bağlantını təmin edəcək.
     */
    public function __construct() {

        $config = include CONFIG . 'database.php';
        $this->connect  =   @mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name'])
                        or  alert([
                            'title' => '"MySQL" xətası!',
                            'hint' => 'Bağlantı qurulmadı.'
                        ]);
        @mysqli_set_charset($this->connect, $config['db_char']);
    }

    /**
     *  MySQL sorğunu icra edəcək.
     * 
     *  @param string $statement
     *  @return object
     */
    public function query(string $statement) : object {

        $this->query = mysqli_query($this->connect, $statement);
        if(!$this->query) alert([
            'title' => '"MySQL" xətası!',
            'hint' => 'Sorğu icra olunmadı.'
        ]);
        return $this;
    }

    /**
     *  Sorğudan alınan sətir sayını döndürəcək.
     * 
     *  @return int
     */
    public function count() : int {

        return (int) mysqli_num_rows($this->query);
    }

    /**
     *  Sətir məlumatlarını döndürəcək.
     * 
     *  @return object
     */
    public function row() : object {

        return (object) mysqli_fetch_object($this->query);
    }

    /**
     *  Bütün sətir məlumatlarını döndürəcək.
     * 
     *  @return object
     */
    public function results() : object {

        $rows = [];
        while($row = mysqli_fetch_object($this->query)) {

            $rows[] = $row;
        }
        return (object) $rows;
    }
}