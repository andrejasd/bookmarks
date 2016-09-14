<?php

class Preview{
    // отримання контенту за допомогою curl
    private function curl_get_file_contents($url)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_URL, $url);
        $contents = curl_exec($c);
        curl_close($c);
        return $contents;
    }

    // превьюха
    public static function create_image($url, $file_name){

/*        // превьюха через www.mini.s-shot.ru
        $api = 'http://mini.s-shot.ru/1280x800/400/jpeg/?';

        //$url =  urlencode($url);
        //$content = file_get_contents($api.$url); // получение картинки
        $content = self::curl_get_file_contents($api.$url);

        if ($content){
            $fp = fopen('uploads'.DS.'preview'.DS.$file_name.'.jpg', 'w'); // Создаем файл с нужным нам именем в нужном месте
            fwrite($fp, $content); // записываем в этот файл содержимое, которое отдал нам сервис
            fclose($fp); // закрываем файл
            return true;
        }else{
            return false;
        }
*/

        // превьюха через phantomjs
        $shell = ROOT.'/webroot/js/phantomjs '.ROOT.'/webroot/js/rasterize.js '.$url.' '.ROOT.'/webroot/uploads/preview/'.$file_name.'.jpg'.' '.'400px*250px 0.3125';
        $result = shell_exec($shell);
        if (!$result || $result == ''){
            return false;
        }else{
            return true;
        }
    }

    // удаление картинки
    public static function delete_image($file_name){
        $file_name = 'uploads'.DS.'preview'.DS.$file_name.'.jpg';
        if (unlink ($file_name)){
            return true;
        }
        else{
            //пишем лог
        }
    }

    // определение заголовка страници
    public static function get_title($url){

        //$url =  urlencode($url);
        //$content = file_get_contents($url);

        //$content = self::curl_get_file_contents($url);

        $shell = ROOT.'/webroot/js/phantomjs '.ROOT.'/webroot/js/title.js '.$url;
        $title = shell_exec($shell);
        if ($title === False || $title == '') {
            $title = "Перевірте посилання";
        }
        else{
            $title = mb_convert_encoding($title, "UTF-8");
        }
        return $title;

//        if ($content === False){
//            $title = "Перевірте посилання";
//        }
//        else{
//            preg_match_all("|<title(.*)>(.*)</title>|sUSi", $content, $matches);
//            $title = $matches[2][0];
//            $title = mb_convert_encoding($title, "UTF-8");
//            // зробити: якщо в title є ?? то виводимо url
//        }
//        return $title;
    }

}