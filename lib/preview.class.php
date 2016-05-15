<?php

class Preview{

    public static function create_image($url, $fname){
        $api = 'http://mini.s-shot.ru/1280x800/400/jpeg/?';
        $url =  urlencode($url);
        @$fp = fopen('uploads'.DS.'preview'.DS.$fname.'.jpg', 'w'); // Создаем файл с нужным нам именем в нужном месте
        @fwrite($fp, file_get_contents($api.$url)); // записываем в этот файл содержимое, которое отдал нам сервис
        @fclose($fp); // закрываем файл

        return true;
    }






}