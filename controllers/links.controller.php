<?php

class LinksController extends Controller{

    public function __construct($data = array()){
        parent::__construct($data);
        $this->model = new Link();
    }

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

                // записуем название сайта если незадано пользователем
                if ( !isset($_POST['title'])){
                    $title = Preview::get_title($url);
                    $this->model->set_link_title($id, $title);
                }

            }
            else {
                Session::setFlash('Error.');
            }

            Router::redirect('/');
        }
    }

    public function link_delete(){ // for ajax
        if ( $_POST ) {
            $id = $_POST['id'];
            $result = $this->model->delete_link($id);
            die;
        }
    }

    public function link_refresh(){ // for ajax
        if ( $_POST ) {
            $id = $_POST['id'];
            $link = $this->model->getLinkById($id);
            $url = $link['url'] ;
            $fname = $id;

            if (!Session::get('id'))
                $fname = 'def_'.$fname;

            Preview::create_image($url, $fname);

            if (!$link['title']){
                $title = Preview::get_title($url);
                $this->model->set_link_title($id, $title);
            }

            $data = array(
                'url' => $link['url'],
                'title' => $link['title']
            );
            echo json_encode($data);
            die;
        }
    }

    public function link_edit(){ // for ajax
        if ( $_POST ){
            //print_r($_POST); exit();
            $link_id = $_POST['link_id'];
            $result1 = $this->model->set_link_url($link_id, $_POST['link']);
            $result2 = $this->model->set_link_title($link_id, $_POST['title']);
            if ( $result1 && $result2) {
                Session::setFlash('Link was edited.');
            }
            else {
                Session::setFlash('Error.');
            }
            Router::redirect('/');
        }
    }

    public function getLinkData(){ // for ajax
        if ( $_POST ) {
            $id = $_POST['id'];
            $link = $this->model->getLinkById($id);
            $data = array(
                'url' => $link['url'],
                'title' => $link['title']
            );
            echo json_encode($data);
            die;
        }
    }

}
