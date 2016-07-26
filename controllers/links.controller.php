<?php

class LinksController extends Controller{

    public function __construct($data = array()){
        parent::__construct($data);
        $this->model = new Link();
    }

    // добавление ссылки в визуальные закладки пользователем
    public function link_add(){ // for ajax
        if ( $_POST ){
            // запись даных в базу
            $result = $this->model->add_link($_POST);
            if ($result) {
                // создание картинки
                $url = $_POST['link'];
                $title = $_POST['title'];
                $id = $result[0][0];
                //$fname = $id;
                //Preview::create_image($url, $fname);
                //Preview::create_image_phantomjs($url, $fname);

                // записуем название сайта если незадано пользователем
                if ( !isset($_POST['title']) || empty($_POST['title']) ){
                    $title = Preview::get_title($url);
                    $this->model->set_link_title($id, $title);
                }

                $data = array(
                    'id' => $id,
                    'url' => $url,
                    'title' => $title
                );

                echo json_encode($data);
            }
            else {
                echo false;
            }

            die;
        }
    }

    // создание превьшки после аякс запроса
    public function create_image(){ // for ajax
        if ( $_POST ){
            $url = $_POST['url'];
            $fname = $_POST['id'];
            Preview::create_image($url, $fname);
        }
    }

    public function link_delete(){ // for ajax
        if ( $_POST ) {
            $id = $_POST['id'];
            $result = $this->model->delete_link($id);
            if ($result){
                $fname = $id;
                Preview::delete_image($fname);
                return true;
            }
        }
        return false;
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
            else{
                $title = $link['title'];
            }

            $data = array(
                'url' => $link['url'],
                'title' => $title,
                'fname' => 'uploads'.DS.'preview'.DS.$fname.'.jpg'
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
          //  Router::redirect('/');
            die;
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

    public function tab_add(){ // for ajax
        if ( $_POST ){
            // запись даных в базу
            $result = $this->model->add_tab($_POST);
            if ($result) {
                $id = $result[0][0];
                $title = $_POST['title'];
            }
            else {
                Session::setFlash('Error.');
            }

            $data = array(
                'id' => $id,
                'title' => $title
            );

            echo json_encode($data);

            die;
        }
    }

    // переименовывает вкладку
    public function tab_rename(){ // for ajax
        if ( $_POST ){
            $id = $_POST['id'];
            $title = $_POST['title'];
            $result = $this->model->rename_tab($id, $title);
            die;
        }
    }

    // удалляет вкладку
    public function tab_delete(){ // for ajax
        if ( $_POST ){
            $tab_id = $_POST['tab_id'];
            $full_delete = $_POST['full_delete'];
            $links_of_tab = $this->model->getUserLinksByTabId($tab_id);
            echo json_encode($links_of_tab);
            if ($full_delete === true){ // удаляем все ссылки
                foreach ($links_of_tab as $link){
                    $link_id = $link['id'];
                    $this->model->delete_link($link_id);
                }
            }else{ // переносим ссылки на другую вкладку
                $move_link_tab = $_POST['move_link_tab'];
                foreach ($links_of_tab as $link) {
                    $link_id = $link['id'];
                    $this->model->link_change_tab($link_id, $move_link_tab);
                }
            }
            $this->model->delete_tab($tab_id);
        }
    }

} //end of class
