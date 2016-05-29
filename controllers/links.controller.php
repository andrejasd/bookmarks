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
            $fname = $id;
            //?????????
            if (!Session::get('id'))
                $fname = 'def_'.$fname;

            //echo '<pre>'; var_dump($fname);  exit();

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
        if (isset($this->params[0])) {
            $id = $this->params[0];
            $link = $this->model->getLinkById($id);
            $data = array(
                'url' => $link['url'],
                'title' => $link['title']
            );
            echo json_encode($data);
        }
    }

    public function addNewLink(){ //for ajax
        if (isset($this->params[0])) {
            // запись даных в базу
            $data = array(
                'link' => $this->params[0],
                'title' => ''
            );

            $result = $this->model->add_link($data);

            var_dump($data);
/*
            if ($result) {
                // создание картинки
                $id = $result[0][0];
                Preview::create_image($link, $id);

                if ($title == '') {
                    $title = Preview::get_title($link);
                    $this->model->set_link_title($id, $title);
                }
            }
*/
        }
    }

}
