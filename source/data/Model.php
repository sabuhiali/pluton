<?php

namespace Pluton\Data;

use \Pluton\Data\Database;
use \Pluton\Library\Session;

class Model {

    /**
     *  Database sinfini tutacaq.
     * 
     *  @var object
     */
    protected $db;

    /**
     *  SQL cümləsini tutacaq.
     * 
     *  @var string
     */
    private static $statement;

    /**
     *  Sütun adlarını tutacaq.
     * 
     *  @var array
     */
    private $tableFields = [];

    /**
     *  
     */
    public $auth = false;

    /**
     *  Kontsruktor
     */
    public function __construct() {

        $this->db = new Database;
        $this->session = new Session;
    }

    /**
     *  Meyar dəyərləri alacaq.
     * 
     *  @param string $tableField
     *  @param $fieldValue
     *  @param $compare
     *  @return object
     */
    public function where(string $tableField, $filedValue, $compare = null) : object {

        list($field, $value, $opr) = argsToString($tableField, $filedValue, $compare);
        if(empty($opr)) 
            self::$statement .= " WHERE `{$field}` = '{$value}'";
        else 
            self::$statement .= " WHERE `{$field}` {$opr} '{$value}'";
        return new static();
    }

    /**
     *  Meyar dəyərləri `AND` ilə davam edəcək. 
     * 
     *  @param string $tableField
     *  @param $fieldValue
     *  @param $compare
     *  @return object
     */
    public function and(string $tableField, $filedValue, $compare = null) : object {

        list($field, $value, $opr) = self::argsToString($tableField, $filedValue, $compare);
        if(empty($opr)) 
            self::$statement .= " AND `{$field}` = '{$value}'";
        else 
            self::$statement .= " AND `{$field}` {$opr} '{$value}'";
        return new static();
    }

    /**
     *  Seçilən sütuna görə alacaq.
     * 
     *  @param string $tableField
     *  @param string $ordering
     *  @return object
     */
    public function by(string $tableField, string $ordering = 'ASC') : object {

        list($field, $order) = self::argsToString($tableField, $ordering);

        $order = strtoupper($order);
        self::$statement .= " ORDER BY `{$field}` {$order}";

        return new static();
    }

    /**
     *  Limitləmə və səhifələmə dəyərini alacaq.
     * 
     *  @param int $limit
     *  @return object
     */
    public function limit(int $limit) : object {

        self::$statement .= " LIMIT {$start}";
        return new static();
    }

    /**
     *  Səhifələmə dəyərlərini alacaq.
     * 
     *  @param int $start
     *  @param int $limit
     *  @return object
     */
    public function paginate(int $start, int $limit) : object {

        $start = $start * $limit - $limit;
        self::$statement .= " LIMIT {$start}, {$limit}";
        return new static();
    }

    /**
     *  Bütün sətir məlumatlarını döndürəcək.
     * 
     *  @return object
     */
    public function getAll() : object {

        $query = "SELECT * FROM `{$this->table}`" . self::$statement;
        return $this->db->query($query)->results();
    }

    /**
     *  Sətir məlumatlarını döndürəcək.
     * 
     *  @return object
     */
    public function getRow() : object {
        
        $query = "SELECT * FROM `{$this->table}`" . self::$statement;
        return $this->db->query($query)->row();
    }

    /**
     *  Sətir sayını döndürəcək.
     * 
     *  @return int
     */
    public function getCount() : int {
        
        $query = "SELECT * FROM `{$this->table}`" . self::$statement;
        return $this->db->query($query)->count();
    }

    /**
     *  Törəyən sinfin dəyişənlərini döndürəcək.
     * 
     *  @return object
     */
    private static function calledClassVars() : object {

        /**
         *  Törəyən sinfin adı.
         * 
         *  @var string
         */
        $childClass = get_called_class();
        return (object) get_object_vars(new $childClass);
    }

    /**
     *  Model xəritəsinə aid olan `protected` olmayan
     *  və tipi `string` və say olan dəyərləri alacaq.
     * 
     *  @return void
     */
    private function buildMap() : void {

        foreach($this as $var => $value) {
            
            if(
                (is_string($value) || is_numeric($value))
                and $var !== 'primaryKey'
                and $var !== 'table'
                and $var !== 'fields'
            ) $this->tableFields[$var] = $value;
        }
    }

    /**
     *  INSERT
     * 
     *  Gələn məlumatları təyin olunan tabloya əlavə edəcək.
     */
    public function add() {

        $this->buildMap();
        $query = $this->db->query("
            INSERT INTO `{$this->table}`
                (". tableFieldParser($this->tableFields) .")
            VALUES
                (". tableColumnParser($this->tableFields) .")
        ");
        if($query) return true;
    }

    /**
     *  UPDATE
     * 
     *  Gələn məlumatları aldığı dəyərə görə redaktə edəcək.
     *  
     *  @param int $id
     */
    public function save(int $id) {

        $this->buildMap();
        $id = (string) $id;
        $query = $this->db->query("
            UPDATE `{$this->table}`
            SET ". tableDataMatcher($this->tableFields, ',') ."
            WHERE `{$this->primaryKey}` = '{$id}'
        ");
        if($query) return true;
    }

    /**
     *  Aldığı dəyərə görə sətrin məlumatlarını döndürəcək.
     * 
     *  @param int $id
     *  @return object
     */
    public static function get(int $id) : object {

        $vars = self::calledClassVars();
        $id = (string) $id;
        return (new Database)->query("SELECT * FROM `{$vars->table}` WHERE `{$vars->primaryKey}` = '{$id}'")->row();
    }

    /**
     *  Təyin olunan tablodan bütün sətir məlumatlarını döndürəcək.
     *  
     *  @param string $order
     *  @return object
     */
    public static function all(string $order = 'ASC') : object {

        $vars = self::calledClassVars();
        $order = strtoupper($order);
        return (new Database)->query("SELECT * FROM `{$vars->table}` ORDER BY `{$vars->primaryKey}` {$order}")->results();
    }

    /**
     *  Təyin olunan tablodan sətir sayını döndürəcək.
     * 
     *  @return int
     */
    public static function count() : int {

        $vars = self::calledClassVars();
        return (new Database)->query("SELECT * FROM `{$vars->table}`")->count();
    }

    /**
     *  Aldığı məlumatları təsdiqləyib sessiya başladacaq.
     * 
     *  @param string $sessionKey
     *  @return void
     */
    public function auth(string $sessionKey = null) : void {

        $this->buildMap();

        //  Sessiya açarsözü mövcuddursa!
        if(isset($sessionKey)){
            
            $query = $this->db->query("
            SELECT * FROM `{$this->table}`
            WHERE ". tableDataMatcher($this->tableFields, ' AND '));
            $row = $query->row();
            $primary = $this->primaryKey;

            if($query->count() > 0) {

                //  Nəticə varsa təsdiqləməni doğru təyin etsin.
                $this->auth = true;

                /**
                 *  `fields` massivi ilə gələn dəyər boş
                 *  olacağı təqdirdə sadəcə açarsözü və
                 *  `primaryKey`i sessiyaya əlavə edəcək.
                 */
                $this->session->set([
                    $sessionKey => true,
                    $this->primaryKey => $row->$primary
                ]);
                
                /**
                 *  `fields` massivi ilə icazə verilən sütunlar təyin
                 *   olunacağı təqdirdə sessiyaya əlavə olunacaq.
                 */
                if(count($this->fields) >= 1) {

                    foreach($this->fields as $field) {

                        $this->session->set([
                            $field => $row->$field
                        ]);
                    }
                }  
            }
        }else {

            //  Açarsöz xətası!
            alert([
                'title' => '"Session" xətası!',
                'hint' => 'Sessiya açar sözü tələb olunur.'
            ]);
        }  
    }
}

/**
 *  where()
 *  and()
 *  by()
 *  limit()
 *  getAll()
 *  getRow()
 *  getCount()
 *  add()
 *  save()
 *  get()
 *  all()
 *  auth()
 */