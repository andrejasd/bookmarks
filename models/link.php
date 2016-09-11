<?php

class Link extends Model{

    protected $user_id;  // в model??

    public function __construct(){
        parent::__construct();
        if ( Session::get('id') )
            $this->user_id = Session::get('id');
    }

    // получение списка ссылок для незарегестрированного пользователя
    public function getDefaultLinks(){
        // возвращает список стандартных ссылок для незарегестрированного пользователя
        $sql = "select * from `default_links` where 1";
        return $this->db->query($sql);
    }

    // получение списка вкладок для незарегестрированного пользователя
    public function getDefaultTabs(){
        $sql = "select `id`,`title` from `default_tabs`";
        return $this->db->query($sql);
    }

    // возвращает список всех ссылок пользователя
    public function getUserLinks(){
        $sql = "select * from `favorites_links` where `user_id` = '{$this->user_id}'";
        return $this->db->query($sql);
    }

    // возвращает список ссылок пользователя принадлежащих вкладке
    public function getUserLinksByTabId($tab_id){
        $sql = "select * from `favorites_links` where `user_id` = '{$this->user_id}' and `tab_id` = '{$tab_id}'";
        return $this->db->query($sql);
    }

    // выыод ссылки по ID
    public function getLinkById($id){
        $table = ($this->user_id) ? 'favorites_links' : 'default_links';
        $sql = "select * from {$table} where `id` = '{$id}' limit 1";
        return $this->db->query($sql)[0];
    }

    // вывод списка вкладок пользователя
    public function getUserTabs(){
        $sql = "select `id`,`title` from `tabs` where `user_id` = '{$this->user_id}'";
        return $this->db->query($sql);
    }

    // запись в БД новой ссылки
    public function add_link($data){
        if ( !isset($data['link']) ){
            return false;
        }
        $link = $this->db->escape($data['link']);
        $title = $this->db->escape($data['title']);
        $tab_id = $this->db->escape($data['tab_id']);
        $sql =  /** @lang text */
            "insert into favorites_links
                    set url = '{$link}',
                        title = '{$title}',
                        user_id = '{$this->user_id}',
                        tab_id = '{$tab_id}';
                    select @@IDENTITY;
                ";
        return $this->db->multi_query($sql);
    }

    // удаление ссылки из БД пользователем
    public function delete_link($link_id){
        $link_id = (int)$link_id;
        $sql = "delete from favorites_links where id = {$link_id} AND `user_id` = {$this->user_id}";
        return $this->db->query($sql);
    }

    // записывает в БД тайтл ссылки
    public function set_link_title($link_id, $title){
        $id = (int)$link_id;
        $sql = /** @lang text */
            "   UPDATE favorites_links
                SET title = '{$title}'
                WHERE id = '{$id}'
            ";
        return $this->db->query($sql);
    }

    // получение из БД тайтла ссылки
    public function get_link_title($link_id){
        $id = (int)$link_id;
        $sql = "SELECT `title` FROM `favorites_links` WHERE `user_id` = '{$this->user_id}' AND `id` = '{$id}' LIMIT 1";
        return $this->db->query($sql)[0]['title'];
    }

    // записывает в БД юрл ссылки
    public function set_link_url($link_id, $url){
        $id = (int)$link_id;
        $sql = /** @lang text */
            "   UPDATE favorites_links
                SET url = '{$url}'
                WHERE id = '{$id}'
            ";
        return $this->db->query($sql);
    }

    // получение из БД юрл ссылки
    public function get_link_url($link_id){
        $id = (int)$link_id;
        $sql = "SELECT `url` FROM `favorites_links` WHERE `user_id` = '{$this->user_id}' AND `id` = '{$id}' LIMIT 1";
        return $this->db->query($sql)[0]['url'];
    }

    // запись в БД новой вкладки
    public function add_tab($data){
        if ( !isset($data['title']) ){
            return false;
        }
        $title = $this->db->escape($data['title']);
        $sql = "
            insert into tabs
                    set title = '{$title}',
                        user_id = '{$this->user_id}';
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

    // удаление вкладки из БД
    public function delete_tab($tab_id){
        $tab_id = (int)$tab_id;
        $sql = "delete from `tabs` where id = {$tab_id} AND `user_id` = {$this->user_id}";
        return $this->db->query($sql);
    }

    public function link_change_tab($link_id, $tab_id){
        $sql =  "
                UPDATE `favorites_links`
                SET `tab_id` = '{$tab_id}'
                WHERE `id` = '{$link_id}'
                AND `user_id` = '$this->user_id'
                ";
        return $this->db->query($sql);
    }


}