<?php

namespace Pluton\Http;

class Router {

    /**
     *  Rota təyinatlarının qeyd olunduğu 
     *  fayl adlarını tutacaq.
     * 
     *  @var array
     */
    private static $routingFiles;

    /**
     *  Middleware təyin olunan faylları tutacaq.
     * 
     *  @var array
     */
    private static $middlewareFiles = [];

    /**
     *  Rota təyinatlarını tutacaq.
     * 
     *  @var array
     */
    private static $routes = [];

    /**
     *  Middleware təyinatlarını tutacaq.
     * 
     *  @var array
     */
    private static $middlewares = [];

    /**
     *  Rota təyinatının mövcudluq dəyəri.
     * 
     *  @var bool
     */
    private static $found = false;

    /**
     *  Rota təyinatlarının qeyd olunğuğu fayl
     *  adını və middleware açar sözünü alacaq.
     * 
     *  @param string $configName
     *  @param string $middlewareKey
     *  @return void
     */
    public static function set(string $configName, string $middlewareKey = null) : void {

        /**
         *  Qəbul olunan hər bir config fayl özü ilə
         *  döndürülən massiv təyinatları gətirəcək.
         * 
         *  @var array
         */
        $file = HTTP . 'routes/' . $configName . '.php';
        if(file_exists($file)) $configFile = include $file;
        else die('rota fayli yoxdur');

        //  Təyinatları $routes massivinə yerləşdirmə.
        self::routerSetter($configFile);

        if(isset($middlewareKey)) self::middlewareSetter($middlewareKey, $configFile); 
    }

    /**
     *  Daxil olunacaq ən az 1 fayl olacağı üçün $routingFiles massivi
     *  içərisində indekslənmə olacaq. Daha sonra həmin massiv dövrə
     *  salınaraq $routes massivinə uyğun dəyərlər yerləşəcək.
     * 
     *  @param array $configFiles
     *  @return void
     */
    private static function routerSetter(array $configFiles) : void {

        /** 
         *  Ən azı bir array tipində dönən fayl qəbul olunacağı üçün
         *  $routingFiles massivinə yerləşir. Bir və daha artıq
         *  rota təyinatı olacağı üçün bu massiv indeksinə görə
         *  dövrə salınaraq içərisindəki massiv dəyərlər $routes
         *  massivinə yerləşəcək.
         */
        self::$routingFiles[] = $configFiles;

        //  İndeksə görə dövrə salma.
        for($i = 0; $i < count(self::$routingFiles); $i++) {

            /**
             *  $routingFiles massivini metod və metoda uyğun
             *  rota təyinatlarına görə dövrə salmaq.
             */
            foreach(self::$routingFiles[$i] as $method => $routes) {

                /**
                 *  $routes massivini http istiqaməti və həmin
                 *  istiqamətə aid kontrollerə görə massivə salmaq.
                 */
                foreach($routes as $uri => $controller) {

                    //  Təyinatların yerləşməsi.
                    self::$routes[$method][$uri] = $controller;
                }
            }
        }
    }

    /**
     *  Daxil olunacaq ən az 1 fayl olacağı üçün $middlewareFiles massivi
     *  içərisində indekslənmə olacaq. Daha sonra həmin massiv dövrə
     *  salınaraq $routes massivinə uyğun dəyərlər yerləşəcək.
     * 
     *  @param string $keyName
     *  @param array $configFiles
     *  @return void
     */
    private static function middlewareSetter(string $keyName, array $configFiles) : void {

        //  İndekslənmə.
        self::$middlewareFiles[] = $configFiles;
        
        //  İndeksə görə dövrə salmaq.
        for($i = 0; $i < count(self::$middlewareFiles); $i++) {

            //  Metod və təyinatlarına görə dövrə salmaq.
            foreach(self::$middlewareFiles[$i] as $method => $routes) {

                //  İstiqamət və kontrollerə görə dövrə salmaq.
                foreach($routes as $uri => $controller) {

                    // Təyinatların yerləşməsi.
                    self::$middlewares[$keyName][$uri] = $method;
                }
            }
        }
    }

    /**
     *  Middleware və rota təyinatlarını icra edəcək.
     * 
     *  @return void 
     */
    public static function execute() : void {

        //  Təyin olunmuş middleware varsa.
        if(count(self::$middlewares) >= 1) {

            //  Açar söz və təyinata görə dövrə salınsın.
            foreach(self::$middlewares as $midKey => $midRoutes) {

                //  İstiqamət və metoda görə dövrə salınsın.
                foreach($midRoutes as $midUri => $midMethod) {

                    /**
                     *  Təyin olunmuş istiqamət və metod mövcud http
                     *  istiqaməti və metodu ilə uyğun gəlirsə.
                     */
                    if(
                        preg_match(convertParams($midUri), $_SERVER['REQUEST_URI']) === 1 and
                        $midMethod === $_SERVER['REQUEST_METHOD']
                    ) {

                        $mid_config = include CONFIG . 'middlewares.php';
                        $middlewareClass = '\App\Middlewares\\' . $mid_config[$midKey];

                        (new $middlewareClass)->handle();

                        //  Uyğun gəldiyi halda dövr sonlansın.
                        break;
                    } 
                }
            }
        }

        /**
         *  Rota təyinatlarının mövcud HTTP metoda görə 
         *  istiqamət və detallara dövrə salınması.
         */
        foreach(self::$routes[$_SERVER['REQUEST_METHOD']] as $path => $routingDetails) {

            //  Təyin olunmuş kontroller sinfinin tam adı.
            $controllerClass = '\App\Controllers\\' . $routingDetails[0];

            //  Təyin olunmuş metodun adı.
            $controllerMethod = $routingDetails[1];

            //  Kontroller sinfinin yazıldığı fayl.
            $controllerFile = ROOT . classToDir($controllerClass);

            //  RegEx qaydasına uyğun istiqamət.
            $path = convertParams($path);

            //  Qarşılaşdırma kontrolu.
            if(preg_match($path, $_SERVER['REQUEST_URI'], $values) === 1) {

                //  Nəticə varsa mövcudluq dəyəri doğru təyin olunsun.
                self::$found = true;

                //  $values massivinin ilk dəyərini silinsin.
                array_shift($values);

                //  Faylın mövcudluq kontrolu.
                if(file_exists($controllerFile)) {

                    //  Sİnfin mövcudluq kontrolu.
                    if(class_exists($controllerClass)) {

                        //  Metodun sinifdə mövcudluq kontrolu.
                        if(method_exists($controllerClass, $controllerMethod)) {

                            call_user_func_array([new $controllerClass, $controllerMethod], array_values($values));
                        }else {

                            //  Metod sinifdə mövcud deyil!
                            alert([
                                'title' => '"Controller" xətası!',
                                'hint' => getClassname($controllerClass) . " sinfində {$controllerMethod}() metodu mövcud deyil!"
                            ]);
                        }
                    }else {

                        //  Kontroller sinfi mövdud deyil!
                        alert([
                            'title' => '"Controller" xətası!',
                            'hint' => getClassname($controllerClass) . ' sinfi mövcud deyil!'
                        ]);
                    }
                }else {

                    //  Kontroller fayl mövcud deyil!
                    alert([
                        'title' => '"Controller" xətası!',
                        'hint' => getClassname($controllerClass) . '.php mövcud deyil!'
                    ]);
                }

                //  Nəticə varsa dövr sonlansın.
                break;
            }
        }

        //  Mövcud olmayan rota təyinatı olacağı təqdirdə 404 xətası.
        if(self::$found == false) {

            http_response_code(404);
            alert([
                'title' => '404 Error!',
                'hint' => 'Page not found!'
            ]);
        }

        //  Prosedur sonlansın!!!
        exit;
    }
}