<?php
// Класс для работы с представлениями
class View{

    // даные, котрые передаются из контроллера в представление
    protected $data;

    // путь к текущему файлу представления
    protected $path;

    //???
    protected static function getDefaultViewPath(){
        $router = App::getRouter();
        if ( !$router ){
            return false;
        }
        $controller_dir = $router->getController();
        $template_name = $router->getMethodPrefix().$router->getAction().'.html';

        return VIEWS_PATH.DS.$controller_dir.DS.$template_name;
    }

    public function __construct($data = array(), $path = null){
        if ( !$path ){
            $path = self::getDefaultViewPath();
        }
        if ( !file_exists($path) ){
            throw new Exception('(ASD)Template file is not found in path: '.$path);
        }
        //echo $path;
        $this->path = $path;
        $this->data = $data;
        //echo '<pre>'; print_r($data);echo '</pre>'; echo '<br>';
    }

    public function  render(){
        $data = $this->data;

        ob_start();
        include ($this->path);
        $content = ob_get_clean();

        return $content;
    }

}