<?php

/**
 * Created by Andrey Bondarenko.
 * Date: 17.04.2016
 */

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

$uri = $_SERVER['REQUEST_URI'];

print_r($uri);