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

            $scheme = 'http://';
            $_POST['link'] = parse_url($_POST['link'], PHP_URL_SCHEME) === null ? $scheme . $_POST['link'] : $_POST['link'];

            $result = $this->model->add_link($_POST);
            if ($result) {
                $url = $_POST['link'];
                $title = $_POST['title'];
                $id = $result[0][0];

                // создание картинки
                //$file_name = $id;
                //Preview::create_image($url, $file_name);

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
            echo $fname;
        }
    }

    public function link_delete(){ // for ajax
        if ( $_POST ) {
            $id = $_POST['id'];
            $result = $this->model->delete_link($id);
            if ($result){
                $fname = $id;
                Preview::delete_image($fname);
                return true; // echo??
            }
        }
        return false;
    }

    public function link_refresh(){ // for ajax
        if ( $_POST ) {
            $id = $_POST['id'];
            $link = $this->model->getLinkById($id);
            $url = $link['url'] ;
            $file_name = $id;

            if (!Session::get('id'))
                $file_name = 'def_'.$file_name;

            Preview::create_image($url, $file_name);

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
                'file_name' => 'uploads'.DS.'preview'.DS.$file_name.'.jpg'
            );
            echo json_encode($data);
            die;
        }
    }

    public function link_edit(){ // for ajax
        if ( $_POST ){
            $link_id = $_POST['link_id'];
            $new_link = $_POST['new_link'];
            $new_title = $_POST['new_title'];
            $old_link = $this->model->get_link_url($link_id);
            $old_title = $this->model->get_link_title($link_id);

            $result1 = false;
            $result2 = false;

            // еслм ссылка изменилась
            if ($old_link <> $new_link){
                // запись новой ссылки
                $result1 = $this->model->set_link_url($link_id, $new_link);
                // если тайтл не перезадан вручную
                if ($old_title == $new_title) {
                    $result2 = $this->model->set_link_title($link_id, '');
                    $data['title_null'] = true;
                }
            }
            if ($old_title <> $new_title) {
                $result2 = $this->model->set_link_title($link_id, $new_title);
                $data['title_null'] = false;
            }

            $data['link_ed'] = $result1;
            $data['title_ed'] = $result2;
            echo json_encode($data);
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
