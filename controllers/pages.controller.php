<?php

class PagesController extends Controller{

    public function __construct($data = array()){
        parent::__construct($data);
        $this->model = new Page();
    }

    public function index(){
        $only_published = true;

        $model_bookmark = new Bookmark();

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
            $current_category_id = $model_bookmark->getUserLastCategoryId();
            Session::set('current_category_id', $current_category_id);

            // список категорий закладок
            $bookmarks_categoris = $model_bookmark->getBookmarksCategories();
            $this->data['select_category'] = '';

            $this->data['select_category'].='<option value=0>'.VIEW_LATER.'</option>';

            foreach ($bookmarks_categoris as $key=>$value){
                $kaf = $value['title'];
                $kaf_id = $value['id'];
                $this->data['select_category'].='<option ';
                if ($current_category_id == $kaf_id)
                    $this->data['select_category'].='selected ';
                $this->data['select_category'].='value='.$kaf_id.'>'.$kaf.'</option>';
            }
            $this->data['select_category'].='<option value="new_category" data-toggle="modal">Новая категория</option>';
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

    //---------------------------------------------------------------------------------------------------------

    // добавление ссылки в визуальные закладки пользователем
    public function link_add(){
        if ( $_POST ){
            // запись даных в базу
            $result = $this->model->add_link($_POST);
            if ($result) {
                // создание картинки
                $url = $_POST['link'];
                $id = $result[0][0];
                $fname = $id;
                Preview::create_image($url, $fname);
                //Preview::create_image_phantomjs($url, $fname);

                if ($_POST['title']){
                    $title = $_POST['title'];
                    $this->model->set_link_title($id, $title);
                }
                else{
                    $title = Preview::get_title($url);
                    $this->model->set_link_title($id, $title);
                }

                // выводим сообщение пользователю
                Session::setFlash('Link was added.');
            }
            else {
                Session::setFlash('Error.');
            }

            Router::redirect('/');
        }
    }

    public function link_delete(){
        if ( isset($this->params[0]) ) {
            $result = $this->model->delete_link($this->params[0]);
            Router::redirect('/');
        }
    }

    public function link_refresh(){
        if ( isset($this->params[0]) ) {
            $id = $this->params[0];
            $link = $this->model->getLinkById($id);
            $url = $link['url'] ;
            //echo '<pre>'; var_dump($url); exit();
            $fname = $id;
            //?????????
            //if (!Session::get('id'))
                //$fname = 'def_'+$fname;
            Preview::create_image($url, $fname);

            if (!$link['title']){
                $title = Preview::get_title($url);
                $this->model->set_link_title($id, $title);
            }

            Router::redirect('/');
        }
    }

    public function link_edit(){
        if ( $_POST ){
            /*
            // запись даных в базу
            $result = $this->model->add_link($_POST);
            if ($result) {
                // создание картинки
                $url = $_POST['link'];
                $fname = $result[0][0];
                Preview::create_image($url, $fname);
                //Preview::create_image_phantomjs($url, $fname);
                // выводим сообщение пользователю

                Session::setFlash('Link was added.');
            }
            else {
                Session::setFlash('Error.');
            }
            */
            Router::redirect('/');
        }
    }

   

    public function test(){
        Session::setFlash('Test flash message');
        Router::redirect('/');
    }

}