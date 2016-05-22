<?php

class User extends Model {

    // получение записи из БД по полю login
    public function getByLogin($email){
        $email = $this->db->escape($email);
        $sql = "select * from users where email = '{$email}' limit 1";
        $result = $this->db->query($sql);
        if ( isset($result[0]) ){
            return $result[0];
        }
        return false;
    }

    // запирсь в БД нового пользователя
    public function setNewUser($email, $psw){

        $email = $this->db->escape($email);
        $psw = $this->db->escape($psw);

        $psw=md5(Config::get('salt').$psw);

        $sql = "
                insert into users
                   set email = '{$email}',
                       password = '{$psw}',
                       role = 'user',
                       is_active = '1'
            ";

        $result = $this->db->query($sql);
     
        return $result;

        //return user id
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

    // список пользователей для админа
    public function getUsers($is_active = true){

        $sql = "select * from users where 1";

        if ( $is_active ){
            $sql .= " and is_active = 1";
        }

        return $this->db->query($sql);
    }
    
}