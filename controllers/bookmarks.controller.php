<?php

class BookmarksController extends Controller{

    public function __construct($data = array()){
        parent::__construct($data);
        $this->model = new Bookmark();
    }

    public function index(){

        $this->data['categories'] = '<a class="breakword list-group-item tabnav" data-toggle="tab" href="#panel0">'.VIEW_LATER.'<a>';
        $this->data['bookmarks'][0] = '';
        $categories = $this->model->getBookmarksCategories();
        foreach($categories as $page_data) {
            $this->data['categories'].='<a class="breakword list-group-item tabnav" data-toggle="tab" href="#panel'.$page_data['id'].'">'.$page_data['title'].'</a>';
            if ( !isset($this->data['bookmarks'][$page_data['id']]) ){
                $this->data['bookmarks'][$page_data['id']] = array();
            };

        };

        $a=1;
        ++$a;

        $user_bookmarks = $this->model->getUserBookmarks();
        foreach ($user_bookmarks as $bookmark){
            $category_id = $bookmark['category_id'];
            $id = $bookmark['id'];
            $url = $bookmark['url'];
            $title = $bookmark['title'];
            if ($title == NULL){
                $title = '';
            }
            // $this->data['bookmarks'][$category_id][]['url'] = "<p><a href='$url'>$url</a></p>";
            // $this->data['bookmarks'][$category_id][]['title'] = "<p><a href='$url'>$title</a></p>";
            $this->data['bookmarks'][$category_id][] = array('url' => "<p><a href='$url'>$url</a></p>", 'title' => "<p><a href='$url'>$title</a></p>");
        }
    }

    public function category_add(){
        if( $_POST ){
            $result = $this->model->add_category($_POST);
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