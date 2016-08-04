<?php

class BookmarksController extends Controller{

    public function __construct($data = array()){
        parent::__construct($data);
        $this->model = new Bookmark();
    }

    public function getUserLastCategory(){
        return $this->model->getUserLastCategoryId()[0]['last_category'];
    }

    public function index(){

        if ( isset($this->params[0]) ) {
            $current_category_id = $this->params[0];
        }else
            $current_category_id = Session::get('current_category_id');

        $this->data['categories'] = '<a href="/bookmarks/index/0" class="breakword list-group-item';
        if (!$current_category_id)
            $this->data['categories'].= ' active';
        $this->data['categories'].= '">'.VIEW_LATER.'</a>';
        $categories = $this->model->getBookmarksCategories();

        foreach($categories as $page_data) {
            $this->data['categories'].='<a href="/bookmarks/index/'.$page_data['id'].'" class="breakword list-group-item';
            if ( ($current_category_id) && ($current_category_id == $page_data['id']) )
                $this->data['categories'].=' active';

            $this->data['categories'].='">'.$page_data['title'].'</a>';
        }

        $this->data['bookmarks'] = $this->model->getUserBookmarksByCategory($current_category_id);
    }

    public function category_add(){
        if( $_POST ){
            $result = $this->model->add_category($_POST);
            if ($result){
                $this->model->setUserLastCategoryId($result);
            }
        }
        Router::redirect('/');
    }

    public function bookmark_add(){
        if( $_POST ){
            $category_id = $_POST['category_id'];
            if ($this->model->add_bookmark( $_POST )){
                $this->model->setUserLastCategoryId($category_id);
            }
        }
        Router::redirect('/');
    }

}