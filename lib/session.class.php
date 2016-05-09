<?php

class Session{
/*
    public function __construct(){
        if( is_null( $_SESSION['flash_message'] ) )
            self::set('flash_message', NULL);
    }
*/
    public static function setFlash($message){
        self::set('flash_message', $message);
        //$_SESSION['flash_message'] = $message;
        //print_r($_SESSION['flash_message']); exit();
    }

    public static function flash(){
        if ( self::get('flash_message') )
            if( !is_null( $_SESSION['flash_message'] ) ){
                echo '<div class="alert alert-info" role="alert">';
                echo $_SESSION['flash_message'];
                echo '</div>';
                //$_SESSION['flash_message'] = null;
            }
    }

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