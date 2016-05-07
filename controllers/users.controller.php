<?php

class UsersController extends Controller{

    public function __construct($data = array()){
        parent::__construct($data);
        $this->model = new User();
    }

    // обработка и вывод формы логина
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

    // уничтожение сессии админа
    public function admin_logout(){
        Session::destroy();
        Router::redirect('/admin/');
    }

    //login юзера
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

    // уничтожение сессии юзера
    public function logout(){
        Session::destroy();
        Router::redirect('/');
    }

    //registration new user
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
                    //Session::setFlash('New user is added. Please confirm your email.');
                    Router::redirect('/users/user_is_added/');
                }
            }
        }
    }

    public function user_is_added(/*$login, $id*/){


        //Session::set('login', $login);
        //Session::set('role', 'user');
        //Session::set('id', $id);
    }


}