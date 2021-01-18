<?php

namespace Pluton\Http;

class Controller {

    /**
     *  @param string $filename
     *  @param array $data
     *  @return void
     */
    protected function view(string $filename, array $data = []) : void {
        
        //  Fayl istiqaməti
        $file = VIEW . $filename . '.phtml';

        //  Mövcudluq kontrolu
        if(file_exists($file)) {

            //  Göndərilən məlumat varsa çıxarış etsin
            if(count($data) >= 0) extract($data); 
            include_once $file;
        }else {

            //  Xəta
            alert([
                'title' => '"View" xətası!',
                'hint' => $filename . '.phtml mövcud deyil!'
            ]);
        }
    }

    /**
     *  Təyin olunan model sinfini döndürəcək
     * 
     *  @param string $model
     *  @return object
     */
    protected function model(string $model) : object {

        //  Gələn dəyərin baş hərfini böyütsün
        $model = ucfirst($model);

        //  Faylın tam istiqaməti
        $model_file = MODEL . $model . '.php';

        //  Sinfin tam adı
        $model_class = '\App\Models\\' . $model;

        //  Faylın mövcudluq kontrolu
        if(file_exists($model_file)) {

            //  Sinfin mövcudluq kontrolu
            if(class_exists($model_class)) {

                return new $model_class;
            }else {

                //  Xəta
                alert([
                    'title' => '"Model" xətası!',
                    'hint' => $model . ' sinfi mövcud deyil!'
                ]);
            }
        }else {

            //  Xəta
            alert([
                'title' => '"Model" xətası!',
                'hint' => $model . '.php mövcud deyil!'
            ]);
        }
    }
}