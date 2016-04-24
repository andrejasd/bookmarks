<?php

class Router
{
    protected $uri;

    protected $controller;

    protected $action;

    protected $params;

    protected $route;

    protected $method_prefix;

    protected $language;

    public function getUri()
    {
        return $this->uri;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getMethodPrefix()
    {
        return $this->method_prefix;
    }
    
    public function __construct($uri)
    {
        //print_r('Ok! Router was called with uri: '.$uri);

        $this->uri = urldecode(trim($uri, '/'));

        // Get defaults
        $routes = Config::get('routes');
        $this->route = Config::get('default_route');
        $this->method_prefix = isset($routes[$this->route]) ? $routes[$this->route] : '';
        $this->controller = Config::get('default_controller');
        $this->action = Config::get('default_action');

        $uri_parts= explode('?', $this->uri);

        // Get path like /controller/action/param1/param2/.../...
        $path = $uri_parts[0];

        $path_parts = explode('/', $path);

        //echo '<pre>'; print_r($path_parts);

        if ( count($path_parts) ){

            // Get route at first element
            if ( in_array(strtolower(current($path_parts)), array_keys($routes)) ){
                $this->route = strtolower(current($path_parts));
                $this->method_prefix = isset($routes[$this->route]) ? $routes[$this->route] : '';
                array_shift($path_parts);
            } 

            // Get controller - nextelement of array
            if ( current($path_parts) ){
                $this->controller = strtolower(current($path_parts));
                array_shift($path_parts);
            }

            // Get action
            if ( current($path_parts) ){
                $this->action = strtolower(current($path_parts));
                array_shift($path_parts);
            }

            // Get params - все решта
            $this->params = $path_parts;

        }

    }

    public static function redirect($location){
        header("Location: $location");
    }
    
}