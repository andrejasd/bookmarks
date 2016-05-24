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
        $current_category_id = Session::get('current_category_id');

        $this->data['categories'] = '<a href="" class="breakword list-group-item';
        if (!$current_category_id)
            $this->data['categories'].= ' active';
        $this->data['categories'].= '">'.VIEW_LATER.'</a>';
        $categories = $this->model->getBookmarksCategories();
        foreach($categories as $page_data) {
            $this->data['categories'].='<a href="" class="breakword list-group-item';
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
                $this->model->setUserLastCategory($result);
            }
        }
        Router::redirect('/');
    }

    public function bookmark_add(){
        if( $_POST ){
            $result = $this->model->add_bookmark( $_POST );
        }
        Router::redirect('/');
    }

}