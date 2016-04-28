<?php

Config::set('site_name', 'My first MVC Site');

//Routes. Rout name => method prefix
Config::set('routes', array(
    'default' => '',
    'admin' => 'admin_'
));

Config::set('default_route', 'default');
Config::set('default_controller', 'pages');
Config::set('default_action', 'index');

Config::set('db.host', 'localhost');
Config::set('db.user', 'root');
Config::set('db.password', '');
Config::set('db.db_name', 'mvc');

Config::set('salt','7815696ecbf1c96e6');

Config::set('log_path',ROOT.DS.'error.log');