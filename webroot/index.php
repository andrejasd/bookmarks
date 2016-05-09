<?php

/**
 * Created by Andrey Bondarenko.
 * Date: 17.04.2016
 */

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));
define('VIEWS_PATH', ROOT.DS.'views');

require_once (ROOT.DS.'lib'.DS.'init.php');

/*
$router = new Router($_SERVER['REQUEST_URI']);

echo '<pre>';
print_r('Route: '.$router->getRoute().PHP_EOL);
print_r('Language: '.$router->getLanguage().PHP_EOL);
print_r('Controller: '.$router->getController().PHP_EOL);
print_r('Action to be called: '.$router->getMethodPrefix().$router->getAction().PHP_EOL);
echo 'Params: ';
print_r($router->getParams());
*/

// Session::setFlash('Test flash message');

session_start();

try {

    App::run($_SERVER['REQUEST_URI']);

} catch (Exception $ex) {
    $msg = ($ex->getMessage());
    $code = ($ex->getCode());
    $file = ($ex->getFile());
    $line = ($ex->getLine());

    $log_msg = "Error $code in $file at line $line: $msg : " . date('d M Y, H:i',time()). PHP_EOL;
    error_log($log_msg, 3, Config::get('log_path'));
    echo $log_msg;

    //Router::redirect('/');
}

//echo '<pre>'; print_r($_SESSION);
$_SESSION['flash_message'] = null;
//Session::set('flash_message', null);