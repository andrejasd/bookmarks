<?php

Config::set('site_name', 'My first MVC Site');

Config::set('languages', array('en', 'ua'));

//Routes. Rout name => method prefix
Config::set('routes', array(
    'default' => '',
    'admin' => 'admin_'
));

Config::set('default_route', 'default');
Config::set('default_language', 'en');
Config::set('default_contoller', 'pages');
Config::set('default', 'index');