<?php

namespace Pluton\Library;

class Pagination {

    /**
     *  Üzərində səhifələmə olacaq istiqamət.
     * 
     *  @var string
     */
    private $currentPath;

    /**
     *  Ümumi say.
     *  Təyin olunan tablonun sətir sayı.
     *  
     *  @var int
     */
    private $dataCount;

    /**
     *  Səhifələnmədə məlumatların limiti.
     * 
     *  @var int
     */
    private $limit;

    /**
     *  Səhifələmə üçün dəyərləri alacaq.
     * 
     *  Səhifələmənin hansı keçid üzərindən işləyəcəyini,
     *  səhifələnəcək tablonun sətir sayını və mövcud
     *  səhifədə nə qədər məlumat göstərəcəyini təyin edən
     *  arqumentləri alacaq.
     * 
     *  @param string $path
     *  @param int $dataCount
     *  @param int $limit
     *  @return void
     */
    public function set(string $path, int $dataCount, int $limit) : void {

        $this->currentPath = preg_replace('/:num/', '(\d+)', '/' . str_replace('/', '\/', $path) . '/');
        $this->dataCount = $dataCount;
        $this->limit = $limit;
    }

    /**
     *  Əvvəlki səhifənin sayını döndürəcək.
     * 
     *  @return int
     */
    public function previousPage() : int {

        /**
         *  set() metodunun ilk aldığı arqumenti mövcud
         *  http istiqaməti ilə qarşılaşdırıb, mövcud
         *  səhifənin sayını alacaq.
         */
        if(preg_match($this->currentPath, $_SERVER['REQUEST_URI'], $match)) {

            //  Qayıdan massivin ilk dəyərini silmək.
            array_shift($match);

            //  Mövcud səhifənin dəyəri.
            $currentPageNumber = (int) $match[0];

            /**
             *  Mövcud səhifə sayı 1-ə bərabərdirsə azalmasın.
             *  Deyilsə azalsın. 
             */
            if($currentPageNumber == 1) return $currentPageNumber; 
            else return $currentPageNumber - 1;
        }
    }

    /**
     *  Növbəti səhifənin sayını döndürəcək.
     * 
     *  @return int
     */
    public function nextPage() : int {

        $currentPageNumber = null;

        /**
         *  set() metodunun ilk aldığı arqumenti mövcud
         *  http istiqaməti ilə qarşılaşdırıb, mövcud
         *  səhifənin sayını alacaq.
         */
        if(preg_match($this->currentPath, $_SERVER['REQUEST_URI'], $match)) {

            //  Qayıdan massivin ilk dəyərini silmək.
            array_shift($match);

            //  Mövcud səhifənin dəyəri.
            $currentPageNumber = (int) $match[0];

            
        }
        //  Səhifə sayı
        $pageCount = ceil($this->dataCount / $this->limit);

        /**
         *  Mövcud səhifə sayı ümumi səhifə sayına
         *  bərabər olarsa artmasın. Kiçikdirsə
         *  artsın.
         */
        if($currentPageNumber == $pageCount) return $currentPageNumber; 
        else return $currentPageNumber + 1;
    }
}


