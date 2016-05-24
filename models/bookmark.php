<?php

class Bookmark extends Model{

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
    public function setUserLastCategoryId($CategoryId){
        $user_id = Session::get('id') ;
        $sql = "update users
                  set last_category_id = '{$CategoryId}'
                where id = {$user_id}
                ";
        return ($this->db->query($sql));
    }

    // чтение последней используемой категории
    public function getUserLastCategoryId(){
        $user_id = Session::get('id') ;
        $sql = "select last_category_id from users where id = '{$user_id}' limit 1";
        //return ($this->db->query($sql));

        $result = $this->db->query($sql);
        if ($result)
            return $result[0]['last_category_id'];
        return false;
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

    // вывод списка закладок пользователя по категории
    public function getUserBookmarksByCategory($category_id, $only_published = false){
        $sql = "select * from bookmarks where user_id = $_SESSION[id] and category_id = $category_id";
        if ( $only_published )
            $sql .= " and is_published = 1";

        return $this->db->query($sql);
    }
}