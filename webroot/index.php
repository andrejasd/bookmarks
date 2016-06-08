<?php

/**
 * Created by Andrey Bondarenko.
 * Date: 17.04.2016
 */

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));
define('VIEWS_PATH', ROOT.DS.'views');
define('VIEW_LATER','Смотреть позже');

require_once (ROOT.DS.'lib'.DS.'init.php');

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
}