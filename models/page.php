<?php

class Page extends Model{


    public function getList($only_published = false){

        // вывод стандартного списка закладок для незарегестрированного пользователя
        if (!Session::get('id'))
        {
            $sql = "select * from default_links where 1";
        }
        // выводсписка закладок пользователя
        else{
            $sql = "select * from favorites_links where user_id = $_SESSION[id]";
        }

        if ( $only_published ){
            $sql .= " and is_published = 1";
        }

        return $this->db->query($sql);
    }
    
    public function getByAlias($alias){
        $alias = $this->db->escape($alias);
        $sql = "select * from pages where alias = '{$alias}' limit 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function getById($id){
        $id = (int)$id;
        $sql = "select * from pages where id = '{$id}' limit 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    // сохранение даных в БД
    public function save($data, $id = null){
        if ( !isset($data['alias']) || !isset($data['title']) || !isset($data['content']) ){
            return false;
        }

        $id = (int)$id;
        $alias = $this->db->escape($data['alias']);
        $title = $this->db->escape($data['title']);
        $content = $this->db->escape($data['content']);
        $is_published = isset($data['is_published']) ? 1 : 0;

        if ( !$id ){ // Add new record
            $sql = "
                    insert into pages
                       set alias = '{$alias}',
                           title = '{$title}',
                           content = '{$content}',
                           is_published = {$is_published}
                ";
        } else { // Update existing record
            $sql = "
                    update pages
                       set alias = '{$alias}',
                           title = '{$title}',
                           content = '{$content}',
                           is_published = {$is_published}
                       where id = {$id}
                ";
        }

        return $this->db->query($sql);
    }

    // удаление даных из БД
    public function delete($id){
        $id = (int)$id;
        $sql = "delete from pages where id = {$id}";
        return $this->db->query($sql);
    }

    // запись в БД новой ссылки
    public function add_link($data){
        if ( !isset($data['link']) ){
            return false;
        }

        $id = Session::get('id') ;
        $link = $this->db->escape($data['link']);
        $title = $this->db->escape($data['title']);

        $sql = "
            insert into favorites_links
                    set url = '{$link}',
                        title = '{$title}',
                        user_id = '{$id}'
                ";

        return $this->db->query($sql);
    }

}