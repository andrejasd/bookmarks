<?php

class Link extends Model{

    public function getDefaultLinks(){
        // возвращает список стандартных ссылок для незарегестрированного пользователя
        $sql = "select * from `default_links` where 1";
        return $this->db->query($sql);
    }

    // возвращает список ссылок пользователя
    public function getUserLinks(){
        $sql = "select * from `favorites_links` where `user_id` = '$_SESSION[id]'";
        return $this->db->query($sql);
    }

    // выыод ссылки по ID
    public function getLinkById($id){
        $sql = "select * from `favorites_links` where `id` = '{$id}' limit 1";
        return $this->db->query($sql)[0];
    }

    // вывод списка вкладок пользователя
    public function getUserTabs(){
        $sql = "select `id`,`title` from `tabs` where `user_id` = '$_SESSION[id]'";
        return $this->db->query($sql);
    }

    // запись в БД новой ссылки
    public function add_link($data){
        if ( !isset($data['link']) ){
            return false;
        }
        $user_id = Session::get('id');
        $link = $this->db->escape($data['link']);
        $title = $this->db->escape($data['title']);
        $tab_id = $this->db->escape($data['tab_id']);
        $sql = "
            insert into favorites_links
                    set url = '{$link}',
                        title = '{$title}',
                        user_id = '{$user_id}',
                        tab_id = '{$tab_id}';
                    select @@IDENTITY;
                ";
        return $this->db->multi_query($sql);
    }

    // удаление ссылки из БД пользователем
    public function delete_link($id){
        $id = (int)$id;
        $sql = "delete from favorites_links where id = {$id}";
        return $this->db->query($sql);
    }

    // записывает в БД тайтл ссылки
    public function set_link_title($link_id, $title){
        //$user_id = Session::get('id');
        $id = (int)$link_id;
        $sql = "
                UPDATE favorites_links
                SET title = '{$title}'
                WHERE id = '{$id}'
            ";
        return $this->db->query($sql);
    }

    // записывает в БД юрл ссылки
    public function set_link_url($link_id, $url){
        $id = (int)$link_id;
        $sql = "
                UPDATE favorites_links
                SET url = '{$url}'
                WHERE id = '{$id}'
            ";
        return $this->db->query($sql);
    }

    // запись в БД новой вкладки
    public function add_tab($data){
        if ( !isset($data['title']) ){
            return false;
        }
        $user_id = Session::get('id');
        $title = $this->db->escape($data['title']);
        $sql = "
            insert into tabs
                    set title = '{$title}',
                        user_id = '{$user_id}';
                    select @@IDENTITY;
                ";
        return $this->db->multi_query($sql);
    }

    // запись в БД нового имени вкладки
    public function rename_tab($id, $title){
        $id = $this->db->escape($id);
        $title = $this->db->escape($title);
        $sql =  "
                UPDATE tabs
                SET title = '{$title}'
                WHERE id = '{$id}'
                ";
        return $this->db->query($sql);
    }
}