<?php

namespace Pluton\Library;

class Validation {

    /**
     *  Gələn və təsdiqlənən məlumatları tutacaq.
     * 
     *  @var array
     */
    private $fields = [];

    /**
     *  $_GET, $_POST, $_REQUEST ilə alınan məlumatların
     *  təyin olunacaq şərtlərini tutacaq.
     * 
     *  @var array
     */
    private $rpg_rules = [];

    /**
     *  Fayl şərtlərini tutacaq.
     * 
     *  @var array
     */
    private $file_rules = [];

    /**
     *  Xətaları tutacaq.
     * 
     *  @var array
     */
    private $errors = [];

    /**
     *  $_GET superqlobal ilə qəbul olunan məlumatları alacaq.
     * 
     *  @param string $field
     *  @param array $rules
     *  @return void 
     */
    public function get(string $field, array $rules = []) : void {

        $this->fields[$field] = $_GET[$field];
        $this->rpg_rules[$field][$_GET[$field]] = $rules;
    }

    /**
     *  $_POST superqlobal ilə qəbul olunan məlumatları alacaq.
     * 
     *  @param string $field
     *  @param array $rules
     *  @return void 
     */
    public function post(string $field, array $rules = []) : void {

        $this->fields[$field] = $_POST[$field];
        $this->rpg_rules[$field][$_POST[$field]] = $rules;
    }

    /**
     *  $_REQUEST superqlobal ilə qəbul olunan məlumatları alacaq.
     * 
     *  @param string $field
     *  @param array $rules
     *  @return void 
     */
    public function request(string $field, array $rules = []) : void {

        $this->fields[$field] = $_REQUEST[$field];
        $this->rpg_rules[$field][$_REQUEST[$field]] = $rules;
    }

    /**
     *  $_FILES superqlobal ilə qəbul olunan məlumatları alacaq.
     * 
     *  @param string $field
     *  @param array $rules
     *  @return void
     */
    public function file(string $field, array $rules = []) : void {

        $this->fields[$field] = $_FILES[$field]['name'];
        $this->file_rules[$field][$_FILES[$field]['name']] = $rules;
    }

    /**
     *  `rpg_rules` və `file_rules` massivində olan məlumatları təsdiqləyəcək.
     * 
     *  @return void
     */
    public function validate() : void {

        /**
         *  REQUEST, POST, GET ilə alınan dəyərlərin kontrolu.
         *  
         *  Sahə və sahəyə aid detallara görə dövrə salmaq.
         */
        foreach($this->rpg_rules as $field => $details) {

            //  Dövrdən alınan detalları dəyər və şərtlərə görə dövrə salma.
            foreach($details as $value => $rules) {

                /**
                 *  ------------------------
                 *  Şərtdə `required` varsa!
                 *  ------------------------
                 */
                if(array_key_exists('required', $rules)) {

                    //  Tələb doğrudursa!
                    if($rules['required'] === true) {

                        //  Məlumat boş deyilsə.
                        if(!empty($value)) {
                            
                            //  Təsdiqlənmiş kimi yenilənsin.
                            $this->fields[$field] = $value;
                        }else {

                            //  Etiket təyin olunubsa!
                            if(array_key_exists('label', $rules)) {

                                //  Bildiriş etiketə görə qeyd olunsun.
                                $this->errors[$rules['label']][] = "Boş olmaz";
                            }else {

                                //  Etiket yoxdursa sahə adına görə qeyd olunsun.
                                $this->errors[$field][] = 'Boş olmaz.';
                            }
                            
                        }
                    }
                }

                /**
                 *  ---------------
                 *  E-mail kontrolu
                 *  ---------------
                 */
                if(array_key_exists('email', $rules)) {

                    //  E-mail tələbi varsa.
                    if($rules['email'] === true) {

                        if(filter_var($value, FILTER_VALIDATE_EMAIL)) {

                            //  Mail adresidirsə əlavə olunsun!
                            $this->fields[$field] = $value;
                        }else {

                            //  Etiket təyin olunubsa!
                            if(array_key_exists('label', $rules)) {

                                //  Bildiriş etiketə görə qeyd olunsun!
                                $this->errors[$rules['label']][] = "E-poçt deyil.";
                            }else {

                                //  Sahə adına görə qeyd olunsun!
                                $this->errors[$field][] = 'E-poçt deyil.';
                            }
                        }
                    }
                }

                /**
                 *  ----------------------------
                 *  Minimum simvol sayı kontrolu
                 *  ----------------------------
                 */
                if(array_key_exists('min_len', $rules)) {

                    //  Muqayisə.
                    if(strlen($value) >= $rules['min_len']) {

                        $this->fields[$field] = $value;
                    }else {

                        //  Etiket təyin olunubsa!
                        if(array_key_exists('label', $rules)) {

                            //  Bildiriş etiketə görə qeyd olunsun!
                            $this->errors[$rules['label']][] = "Simvol sayı azdır.";
                        }else {

                            //  Sahə adına görə qeyd olunsun!
                            $this->errors[$field][] = "Simvol sayı zdır.";
                        }
                    }
                }

                /**
                 *  -----------------------------
                 *  Maksimum simvol sayı kontrolu
                 *  -----------------------------
                 */
                if(array_key_exists('max_len', $rules)) {

                    //  Muqayisə.
                    if(strlen($value) <= $rules['max_len']) {

                        $this->fields[$field] = $value;
                    }else {

                        //  Etiket təyin olunubsa!
                        if(array_key_exists('label', $rules)) {

                            //  Bildiriş etiketə görə qeyd olunsun!
                            $this->errors[$rules['label']][] = "Simvol sayı çoxdur.";
                        }else {

                            //  Sahə adına görə qeyd olunsun!
                            $this->errors[$field][] = "Simvol sayı çoxdur.";
                        }
                    }
                }

                /**
                 *  ---------------
                 *  Boşluq kontrolu
                 *  ---------------
                 */
                if(array_key_exists('space', $rules)) {

                    //  `false` icazə yoxdur!
                    if($rules['space'] === false) {
                        
                        //  Boşluq mövcudluğu.
                        if(!preg_match('/\s/', $value)) {

                            $this->fields[$field] = $value;
                        }else {

                            //  Etiket təyin olunubsa!
                            if(array_key_exists('label', $rules)) {

                                //  Bildiriş etiketə görə qeyd olunsun!
                                $this->errors[$rules['label']][] = "Boşluq buraxmaq olmaz.";
                            }else {

                                //  Sahə adına görə qeyd olunsun!
                                $this->errors[$field][] = "Boşluq buraxmaq olmaz.";
                            }
                        }
                    }
                }

                /**
                 *  ----------------------------
                 *  RegEx uyğunlaşdırma kontrolu
                 *  ----------------------------
                 */
                if(array_key_exists('match', $rules)) {

                    /**
                     *  Uyğunlaşdırılacaq dəyər.
                     * 
                     *  @var string
                     */
                    $rule = $rules['match'][0];

                    /**
                     *  İcazə.
                     * 
                     *  Əgər `true` olarsa dəyərin şərtə uyğun
                     *  gəlməyi mütləqdir.
                     *  `false` olarsa uyğun gəlməməyi.
                     * 
                     *  @var bool
                     */
                    $permission = $rules['match'][1];

                    //  İcazə var!
                    if($permission === true) {

                        if(preg_match($rule, $value)) {

                            $this->fields[$field] = $value;
                        }else {

                            //  Etiket təyin olunubsa!
                            if(array_key_exists('label', $rules)) {

                                //  Bildiriş etiketə görə qeyd olunsun!
                                $this->errors[$rules['label']][] = "Uyğun olmayan simvol var.";
                            }else {

                                //  Sahə adına görə qeyd olunsun!
                                $this->errors[$field][] = "Uyğun olmayan simvol var.";
                            }
                        }
                    }
                    //  İcazə yoxdur!
                    elseif($permission === false) {

                        //  Uyğunsuzluq!s
                        if(!preg_match($rule, $value)) {

                            $this->fields[$field] = $value;
                        }else {

                            //  Etiket təyin olunubsa!
                            if(array_key_exists('label', $rules)) {

                                //  Bildiriş etiketə görə qeyd olunsun!
                                $this->errors[$rules['label']][] = "Uyğun olmayan simvol var.";
                            }else {

                                //  Sahə adına görə qeyd olunsun!
                                $this->errors[$field][] = "Uyğun olmayan simvol var.";
                            }
                        }
                    }
                }
            }
        }

        /**
         *  Fayl kontrolu.
         * 
         *  Sahə və sahəyə aid detallara görə dövrə salmaq.
         */
        foreach($this->file_rules as $field => $details) {
            
            foreach($details as $name => $rules) {

                /**
                 *  --------------------
                 *  Faylın həcm kontrolu
                 *  --------------------
                 */
                if(array_key_exists('size', $rules)) {

                    //  Müqayisə
                    if($_FILES[$field]['size'] <= $rules['size']) {

                        $this->fields[$field] = $_FILES[$field]['name'];
                    }else {
                        
                        //  Etiket kontrolu!
                        if(array_key_exists('label', $rules)) {

                            //  Etiketə görə bildiriş.
                            $this->errors[$rules['label']][] = "Fayl həcmi böyükdür.";
                        }else {

                            //  Sahə adına görə bildiriş.
                            $this->errors[$field][] = "Fayl həcmi böyükdür.";
                        }
                    }
                }

                /**
                 *  -------------------------
                 *  Faylın mütləqlik kontrolu
                 *  -------------------------
                 */
                if(array_key_exists('required', $rules)) {

                    //  Mütləqlik doğru təyin olunubsa!
                    if($rules['required'] === true) {

                        //  Fayl mövcuddursa.
                        if(!empty($_FILES[$field]['name'])) {

                            $this->fields[$field] = $_FILES[$field]['name'];
                        }else {

                            //  Etiket kontrolu!
                            if(array_key_exists('label', $rules)) {

                                //  Etiketə görə bildiriş.
                                $this->errors[$rules['label']][] = "Fayl boşdur.";
                            }else {

                                //  Sahə adına görə bildiriş.
                                $this->errors[$field][] = "Fayl boşdur.";
                            }
                        }
                    }
                }

                /**
                 *  -------------------
                 *  Faylın tip kontrolu
                 *  -------------------
                 */
                if(array_key_exists('type', $rules)) {

                    //  Faylın mövcud tipinin təyin olunanlarda mövcudluğu.
                    if(in_array($_FILES[$field]['type'], $rules['type'])) {

                        $this->fields[$field] = $_FILES[$field]['name'];
                    }else {

                        //  Etiket kontrolu!
                        if(array_key_exists('label', $rules)) {

                            //  Etiketə görə bildiriş.
                            $this->errors[$rules['label']][] = "Fayl tipi uyğun deyil.";
                        }else {

                            //  Sahə adına görə bildiriş.
                            $this->errors[$field][] = "Fayl tipi uyğun deyil.";
                        }
                    }
                }

                /**
                 *  ------------------
                 *  Faylın köçürülməsi
                 *  ------------------
                 */
                if(array_key_exists('save', $rules)) {

                    //  Heç bir xəta yoxdursa!
                    if(count($this->errors) === 0) {

                        //  Fay mövcuddursa!
                        if(isset($_FILES[$field]['name'])) {

                            $dir = ASSET . $rules['save'];
                            $upload = move_uploaded_file($_FILES[$field]['tmp_name'], $dir . $_FILES[$field]['name']);

                            //  Uğurlu yükləmə.
                            if($upload) {

                                $this->fields[$field] = $_FILES[$field]['name'];
                            }else {

                                //  Etiket kontrolu!
                                if(array_key_exists('label', $rules)) {

                                    //  Etiketə görə bildiriş.
                                    $this->errors[$rules['label']][] = "Fayl yüklənmədi.";
                                }else {

                                    //  Sahə adına görə bildiriş.
                                    $this->errors[$field][] = "Fayl yüklənmədi.";
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     *  Məlumatların tsədiqlənməsinin doğru cavabını döndürəcək.
     * 
     *  @return bool
     */
    public function isValid() : bool {

        if(count($this->errors) === 0) return true;
    }

    /**
     *  Təsdiqlənmiş dəyərləri döndürəcək.
     * 
     *  @return object
     */
    public function getKey() : object {

        return (object) $this->fields;
    }

    /**
     *  Xətaları döndürəcək.
     * 
     *  @return array
     */
    public function errors() : array {

        return $this->errors;
    }
}