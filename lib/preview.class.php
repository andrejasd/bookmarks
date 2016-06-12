<?php

class Preview{

    // превьюха через www.mini.s-shot.ru
    public static function create_image($url, $fname){
        $api = 'http://mini.s-shot.ru/1280x800/400/jpeg/?';
        $url =  urlencode($url);

        $fp = fopen('uploads'.DS.'preview'.DS.$fname.'.jpg', 'w'); // Создаем файл с нужным нам именем в нужном месте
        fwrite($fp, file_get_contents($api.$url)); // записываем в этот файл содержимое, которое отдал нам сервис
        fclose($fp); // закрываем файл

        return true;
    }

    // превьюха через phantomjs в linux
    public static function create_image_phantomjs($url, $fname){
        $shell = ROOT.'/webroot/js/phantomjs '.ROOT.'/webroot/js/rasterize.js '.$url.' '.ROOT.'/webroot/uploads/preview/'.$fname.'.jpg';
        //echo $shell; exit();
        shell_exec($shell);
    }

    // определение заголовка страници
    public static function get_title($url){
        $content = file_get_contents($url);

        preg_match_all("|<title(.*)>(.*)</title>|sUSi", $content, $matches);

        $title = $matches[2][0];
        $title = mb_convert_encoding($title, "UTF-8", "auto");

        //echo '<br>'.$title; exit();

        return $title;
    }




}