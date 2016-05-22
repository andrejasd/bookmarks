<?php

class Page extends Model{

    public function getDefaultLinks($only_published = false){
    // вывод стандартного списка ссылок для незарегестрированного пользователя
        $sql = "select * from default_links where 1";
        if ( $only_published )
            $sql .= " and is_published = 1";

        return $this->db->query($sql);
    }

    public function getUserLinks($only_published = false){
    // вывод списка ссылок пользователя
        $sql = "select * from favorites_links where user_id = $_SESSION[id]";
        if ( $only_published )
            $sql .= " and is_published = 1";

        return $this->db->query($sql);
    }

    public function getLinkById($id){
        $sql = "select * from favorites_links where id = {$id}";
        return $this->db->query($sql)[0];
    }
    
    // запись в БД новой ссылки
    public function add_link($data){
        if ( !isset($data['link']) ){
            return false;
        }

        $user_id = Session::get('id');
        $link = $this->db->escape($data['link']);
        $title = $this->db->escape($data['title']);

        $sql = "
            insert into favorites_links
                    set url = '{$link}',
                        title = '{$title}',
                        user_id = '{$user_id}';
                    select @@IDENTITY;
                ";

        return $this->db->multi_query($sql);
    }

    // удаление ссылки из БД пользователем
    public function delete_link($id){
        $id = (int)$id;
        //$sql = "delete from favorites_links where id = {$id}";
        $sql = "update favorites_links
                  set is_published = 0
                where id = {$id}
                ";
        return $this->db->query($sql);
    }
    
    public function set_link_title($link_id, $title){
        //$user_id = Session::get('id');
        $id = (int)$link_id;
        $sql = "
                UPDATE favorites_links
                SET title = '{$title}'
                WHERE id = {$id}
            ";
        return $this->db->query($sql);
    }

    // список пользователей для админа
    public function getUsers($is_active = true){

        $sql = "select * from users where 1";

        if ( $is_active ){
            $sql .= " and is_active = 1";
        }

        return $this->db->query($sql);
    }

    // удаление пользователей из БД для админа
    public function delete_user($id){
        $id = (int)$id;
        //$sql = "delete from users where id = {$id}";
        $sql = "
                update users
                  set is_active = 0
                where id = {$id}
                ";
        return $this->db->query($sql);
    }

    public function getBookmarksCategories(){
        $sql = "select * from bookmarks_categories where user_id = $_SESSION[id]";
        return $this->db->query($sql);
    }

    // запись в БД новой категории
    public function add_category($data){
        if ( !isset($data['category']) ){
            return false;
        }
        $user_id = Session::get('id') ;
        $category_title = $this->db->escape($data['category']);
        $sql = "insert into bookmarks_categories
                        set title = '{$category_title}',
                            user_id = '{$user_id}'
                ";
        if ($this->db->query($sql))
            return $category_title;
    }

    // запись последней используемой категории
    // результат после функции add_category
    public function setUserLastCategory($LastCategory){
        $user_id = Session::get('id') ;
        $sql = "update users
                  set last_category = '{$LastCategory}'
                where id = {$user_id}
                ";
        return ($this->db->query($sql));
    }

    // чтение последней используемой категории
    public function getUserLastCategory(){
        $user_id = Session::get('id') ;
        $sql = "select last_category from users where id = '{$user_id}' limit 1";
        return ($this->db->query($sql));
    }

    public function add_bookmark($data){
        $user_id = Session::get('id') ;
        $category_id = $data['category_id'];
        $url = $data['bookmark'];
        $sql = "insert into bookmarks
                        set category_id = '{$category_id}',
                            user_id = '{$user_id}',
                            url = '{$url}'
                ";
        return ($this->db->query($sql));
    }

    // вывод списка закладок пользователя
    public function getUserBookmarks($only_published = false){
        $sql = "select * from bookmarks where user_id = $_SESSION[id]";
        if ( $only_published )
            $sql .= " and is_published = 1";

        return $this->db->query($sql);
    }

}