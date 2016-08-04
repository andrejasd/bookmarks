<?php

class PagesController extends Controller
{

    public function __construct($data = array())
    {
        parent::__construct($data);
    }

    public function index()
    {
        $model_bookmark = new Bookmark();
        $model_link = new Link();

        // вывод стандартного списка закладок для незарегестрированного пользователя
        if (!Session::get('id')) {
            $this->data['links'] = $model_link->getDefaultLinks();
            $this->data['tabs'] = $model_link->getDefaultTabs();
            $pic_prefix = "def_";
        } else {
            // список линков пользователя
            $this->data['links'] = $model_link->getUserLinks();
            $this->data['tabs'] = $model_link->getUserTabs();

            $this->data['tabs_count'] = count($this->data['tabs']);
            //echo '<pre>';print_r($this->data['tabs']);print_r($this->data['tabs_count']);die;
            $pic_prefix = "";

            // устанавливаем текущую категорию из БД
            $current_category_id = $model_bookmark->getUserLastCategoryId();
            Session::set('current_category_id', $current_category_id);

            // список категорий закладок
            $bookmarks_categoris = $model_bookmark->getBookmarksCategories();
            $this->data['select_category'] = '';

            $this->data['select_category'] .= '<option value=0>' . VIEW_LATER . '</option>';
            foreach ($bookmarks_categoris as $key => $value) {
                $kaf = $value['title'];
                $kaf_id = $value['id'];
                $this->data['select_category'] .= '<option ';
                if ($current_category_id == $kaf_id)
                    $this->data['select_category'] .= 'selected ';
                $this->data['select_category'] .= 'value=' . $kaf_id . '>' . $kaf . '</option>';
            }
            $this->data['select_category'] .= '<option value="new_category" data-toggle="modal">Новая категория</option>';

            // список вкладок
            $this->data['select_tab'] = '';
            foreach ($this->data['tabs'] as $key => $value){
                $kaf = $value['title'];
                $kaf_id = $value['id'];
                $this->data['select_tab'] .= '<option ';
                if ($kaf_id === 1)
                    $this->data['select_tab'] .= 'selected ';
                $this->data['select_tab'] .= 'value=' . $kaf_id . '>' . $kaf . '</option>';
            }

        }

        // добавляем в массив data путь к рисунку-превьюхе при ее наличии
        foreach ($this->data['links'] as $key => $value) {
            $pic_src = 'uploads' . DS . 'preview' . DS . $pic_prefix . $this->data['links'][$key]['id'] . '.jpg';
            if (file_exists($pic_src))
                $this->data['links'][$key]['pic_src'] = $pic_src;
        }
    }

    public function admin_index()
    {
        $model_user = new User();
        $this->data['users'] = $model_user->getUsers();
    }

    public function test()
    {
        //Router::redirect('/');
    }
    
}