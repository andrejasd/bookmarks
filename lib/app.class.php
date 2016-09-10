<?php
// обрабатывает запросы и вызивает методы контроллера
class App{

    protected static $router;

    public static $db;
    
    public static function getRouter()
    {
        return self::$router;
    }

    public static function run($uri){
        self::$router = new Router($uri);

        self::$db = new DB(Config::get('db.host'), Config::get('db.user'), Config::get('db.password'), Config::get('db.db_name'));
        
        $controller_class = ucfirst(self::$router->getController()).'Controller';
        $controller_method = strtolower(self::$router->getMethodPrefix().self::$router->getAction());

        // if admin then admin_login
        $layout = self::$router->getRoute();
        if ( $layout == 'admin' && Session::get('role') != 'admin' ){
            if ( $controller_method != 'admin_login' ){
                Router::redirect('/admin/users/login');
            }
        }

        // Calling controller's method
        $controller_object = new $controller_class();
        if ( method_exists($controller_object, $controller_method) ){
            // Controller's action may return a view path
            $view_path = $controller_object->$controller_method();
            $view_object = new View($controller_object->getData(), $view_path);
            $content = $view_object->render();
            //echo '<pre>'; print_r($content);echo '</pre>'; echo '<br>';
        } else {
            throw new Exception('(ASD)Method '.$controller_method.' of class '.$controller_class.' does not exist');
        }

        $layout = self::$router->getRoute();
        $layout_path = VIEWS_PATH.DS.$layout.'.html';
        $layout_view_object = new View(compact('content'), $layout_path);
        echo $layout_view_object->render();
    }
}