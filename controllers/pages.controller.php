<?php

class PagesController extends Controller{

    public function __construct($data = array()){
        parent::__construct($data);
        $this->model = new Page();
    }

    public function index(){
        $only_published = true;

        // вывод стандартного списка закладок для незарегестрированного пользователя
        if (!Session::get('id'))
        {
            $this->data['links'] = $this->model->getDefaultLinks($only_published);
            $pic_prefix = "def_";
        }
        // выводсписка закладок пользователя
        else{
            $this->data['links'] = $this->model->getUserLinks($only_published);
            $pic_prefix = "";
        }

        // добавляем в массив data путь к превьюхе при ее наличии
        foreach($this->data['links'] as $key=>$value){
            $pic_src = 'uploads'.DS.'preview'.DS.$pic_prefix.$this->data['links'][$key]['id'].'.jpg';
            if ( file_exists ($pic_src) )
                $this->data['links'][$key]['pic_src'] = $pic_src;
        }
    }

    /*
    public function view(){
        $params = App::getRouter()->getParams();

        if ( isset($params[0]) ){
            $alias = strtolower($params[0]);
            $this->data['page'] = $this->model->getByAlias($alias);
        }
    }
    */

    public function admin_index(){
        $this->data['users'] = $this->model->getUsers();
    }

    // добавление статьи
    public function admin_add(){
        if ( $_POST ){
            $result = $this->model->save($_POST);
            if ( $result ){
                Session::setFlash('Page was saved.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/pages/');
        }
    }
/*
    // для редактирования статьи
    public function admin_edit(){

        if ( $_POST ){
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $result = $this->model->save($_POST, $id);
            if ( $result ){
                Session::setFlash('Page was saved.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/pages/');
        }

        //отображение формы ред страници с заполнеными полями ввода
        if ( isset($this->params[0]) ){
            $this->data['page'] = $this->model->getById($this->params[0]);
        } else {
            Session::setFlash('Wrong page id.');
            Router::redirect('/admin/pages/');
        }
    }

    // удаление статьи
    public function admin_delete(){
        if ( isset($this->params[0]) ){
            $result = $this->model->delete($this->params[0]);
            if ( $result ){
                Session::setFlash('Page was deleted.');
            } else {
                Session::setFlash('Error.');
            }
        }
        Router::redirect('/admin/pages/');
    }

*/

    // добавление ссылки пользователем
    public function link_add(){
        if ( $_POST ){
            // запись даных в базу
            $result = $this->model->add_link($_POST);

            //exit( '/uploads/preview/'."{$result[0][0]}".'.jpg' );
            //exit ('<pre>'.print_r($result[0][0]));
            
            //if ($result !== false) {

                // создание картинки
                $api = 'http://mini.s-shot.ru/1280x800/400/jpeg/?';
                $url = $_POST['link'];

                    @$fp = fopen('uploads'.DS.'preview'.DS."{$result[0][0]}".'.jpg', 'w'); // Создаем файл с нужным нам именем в нужном месте
                    @fwrite($fp, file_get_contents($api . $url)); // записываем в этот файл содержимое, которое отдал нам сервис
                    @fclose($fp); // закрываем файл

            //}

            if ( $result ){
                Session::setFlash('Link was added.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect('/');
        }
    }

    // удаление юзера пользователем
    public function admin_delete_user(){
        if ( isset($this->params[0]) ){
            $result = $this->model->delete_user($this->params[0]);
            if ( $result ){
                Session::setFlash('User was deleted.');
            } else {
                Session::setFlash('Error.');
            }
        }
        Router::redirect('/admin/pages/');
    }

}