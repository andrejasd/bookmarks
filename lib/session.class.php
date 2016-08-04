<?php

class Session{
    // запись даных в масив $_SESSION по ключу
    // добавление даных в сессию
    public static function set($key, $value){
        $_SESSION[$key] = $value;
    }

    // получение даных из сессии
    public static function get($key){
        if ( isset($_SESSION[$key]) ){
            return $_SESSION[$key];
        }
        return null;
    }

    // удаление даных из сессии
    public static function delete($key){
        if ( isset($_SESSION[$key]) ){
            unset($_SESSION[$key]);
        }
    }

    // уничтожение сессии
    public static function destroy(){
        session_destroy();
    }

}