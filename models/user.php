<?php

class User extends Model {

    // получение записи из БД по полю login
    public function getByLogin($email){
        $login = $this->db->escape($email);
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

}