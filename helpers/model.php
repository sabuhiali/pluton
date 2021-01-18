<?php

/**
 *  Təyin olunan model sinfini döndürəcək
 * 
 *  @param string $model
 *  @return object
 */
if(!function_exists('model')) {

    function model(string $model) : object {

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