<?php

class UsersController extends Controller{

    public function __construct($data = array()){
        parent::__construct($data);
        $this->model = new User();
    }

    // логин админа
    public function admin_login(){
        if ( $_POST && isset($_POST['email']) && isset($_POST['password']) ){
            $user = $this->model->getByLogin($_POST['email']);
            $hash = md5(Config::get('salt').$_POST['password']);
            if ( $user && $user['is_active'] && $hash == $user['password'] && $user['role'] == 'admin' ){
                Session::set('email', $user['email']);
                Session::set('role', $user['role']);
                Session::set('id', $user['id']);
            }
            Router::redirect('/admin/');
        }
    }

    // завершение сессии админа
    public function admin_logout(){
        Session::destroy();
        Router::redirect('/admin/');
    }

    // login юзера
    public function login(){
        if ( $_POST && isset($_POST['email']) && isset($_POST['password']) ){
            $user = $this->model->getByLogin($_POST['email']);
            $hash = md5(Config::get('salt').$_POST['password']);
            if ( $user && $user['is_active'] && $hash == $user['password'] ){
                Session::set('email', $user['email']);
                Session::set('role', 'user');
                Session::set('id', $user['id']);
            }
            Router::redirect('/');
        }
    }

    // завершение сессии юзера
    public function logout(){
        Session::destroy();
        Router::redirect('/');
    }

    // регистрация нового пользователя
    public function registration(){
        if ( $_POST ){
            $flash_message=NULL;
            if (empty($_POST['email'])){
                $flash_message = 'Please enter your email!';
            }
            if (empty($_POST['psw'])) {
                $flash_message.=' Please enter password!';
            }

            if (!is_null($flash_message)){
                Session::setFlash($flash_message);
            }
            elseif ( ($_POST['psw']) != ($_POST['psw_confirm']) ){
                $flash_message = 'Пароли не совпадают';
            }

            if (!is_null($flash_message)){
                Session::setFlash($flash_message);
            }
            else
            {
                $result = $this->model->setNewUser( $_POST['email'], $_POST['psw']);
                if ($result) {
                    Session::setFlash('New user is added. Please confirm your email.');
                    //Session::set('login', $login);
                    //Session::set('role', 'user');
                    //Session::set('id', $id);
                    Router::redirect('/');
                }
            }
        }
    }

    // удаление юзера админом
    public function admin_delete_user(){
        if ( isset($this->params[0]) ){
            $result = $this->model->delete_user($this->params[0]);
            if ( $result ){
                Session::setFlash('User was deleted.');
            } else {
                Session::setFlash('Error.');
            }
        }
        Router::redirect('/admin/pages/');
    }
    

}