<?php

class App{

    protected static $router;

    /**
     * @return mixed
     */
    public static function getRouter()
    {
        return self::$router;
    }

    public static function run($uri){
        self::$router = new Router($uri);

        //echo '<pre>';        print_r (self::$router);
        //echo
        $controller_class = ucfirst(self::$router->getController()).'Controller';
        //echo
        $controller_method = strtolower(self::$router->getMethodPrefix().self::$router->getAction());

        // Calling controller's method
        $controller_object = new $controller_class();
        if ( method_exists($controller_object, $controller_method) ){
            // Controller's action may return a view path
            $view_path = $controller_object->$controller_method();
            $view_object = new View($controller_object->getData(), $view_path);
            $content = $view_object->render();
        } else {
            throw new Exception('(ASD)Method '.$controller_method.' of class '.$controller_class.' does not exist');
        }

        $layout = self::$router->getRoute();
        $layout_path = VIEWS_PATH.DS.$layout.'.html';
        $layout_view_object = new View(compact('content'), $layout_path);
        echo $layout_view_object->render();
    }

    }