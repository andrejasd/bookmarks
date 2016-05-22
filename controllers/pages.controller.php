<?php

class PagesController extends Controller{

    public function __construct($data = array()){
        parent::__construct($data);
        //$this->model = new Page();
    }

    public function index(){
        $only_published = true;

        $model_bookmark = new Bookmark();
        $model_link = new Link();

        // вывод стандартного списка закладок для незарегестрированного пользователя
        if (!Session::get('id'))
        {
            $this->data['links'] = $model_link->getDefaultLinks($only_published);
            $pic_prefix = "def_";
        }
        else{
            // список линков пользователя
            $this->data['links'] = $model_link->getUserLinks($only_published);
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
        $model_user = new User();
        $this->data['users'] = $model_user->getUsers();
    }

    public function test(){
        Session::setFlash('Test flash message');
        Router::redirect('/');
    }
}