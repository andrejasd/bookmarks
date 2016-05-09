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
        else{
            // список линков пользователя
            $this->data['links'] = $this->model->getUserLinks($only_published);
            $pic_prefix = "";

            // устанавливаем текущауюкатегорию из БД
            Session::set('current_category', $this->model->getUserLastCategory()[0]['last_category']);

            // список категорий закладок
            $bookmarks_categoris = $this->model->getBookmarksCategories();
            $this->data['select_category'] = '';
            foreach ($bookmarks_categoris as $key=>$value){
                $kaf = $value['title'];
                $kaf_id = $value['id'];
                $this->data['select_category'].='<option ';
                if (Session::get('current_category') == $kaf)
                    $this->data['select_category'].='selected ';
                $this->data['select_category'].='value='.$kaf_id.'>'.$kaf.'</option>';
            }
        }

        // добавляем в массив data путь к рисунку-превьюхе при ее наличии
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

    // удаление юзера админом
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
            /*if ( $result ){
                Session::setFlash('Link was added.');
            } else {
                Session::setFlash('Error.');
            }*/
            Router::redirect('/');
        }
    }

    public function link_delete(){
        if ( isset($this->params[0]) ) {
            $result = $this->model->delete_link($this->params[0]);
            Router::redirect('/');
        }
    }

    public function category_add(){
        if( $_POST ){
            $result = $this->model->add_category($_POST);
            if ($result){
                $this->model->setUserLastCategory($result);
            }
        }
        Router::redirect('/');
    }

    public function bookmark_add(){
        if( $_POST ){
            $result = $this->model->add_bookmark( $_POST );
            //echo '<pre>'; print_r($_POST); exit();
        }

        Router::redirect('/');
    }

    public function bookmarks(){
        $this->data['bookmarks'] = $this->model->getUserBookmarks();
 //       echo '<pre>'; print_r ($this->data['bookmarks']); exit();
    }

}