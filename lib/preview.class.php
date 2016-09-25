<?php

class Preview{
    // отримання контенту за допомогою curl
    private function curl_get_file_contents($url)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64)');
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt ($c, CURLOPT_CONNECTTIMEOUT, 0);
        $contents = curl_exec($c);
        curl_close($c);
        return $contents;
    }

    // заміна символів кирилиці і кодування спецсимволів
    private function url_encode($url){
        $url= strtr ($url, array (" "=> "%20",
                                  "а"=>"%D0%B0", "А"=>"%D0%90", "б"=>"%D0%B1", "Б"=>"%D0%91", "в"=>"%D0%B2", "В"=>"%D0%92",
                                  "г"=>"%D0%B3", "Г"=>"%D0%93", "д"=>"%D0%B4", "Д"=>"%D0%94", "е"=>"%D0%B5", "Е"=>"%D0%95",
                                  "ё"=>"%D1%91", "Ё"=>"%D0%81", "ж"=>"%D0%B6", "Ж"=>"%D0%96", "з"=>"%D0%B7", "З"=>"%D0%97",
                                  "и"=>"%D0%B8", "И"=>"%D0%98", "й"=>"%D0%B9", "Й"=>"%D0%99", "к"=>"%D0%BA", "К"=>"%D0%9A",
                                  "л"=>"%D0%BB", "Л"=>"%D0%9B", "м"=>"%D0%BC", "М"=>"%D0%9C", "н"=>"%D0%BD", "Н"=>"%D0%9D",
                                  "о"=>"%D0%BE", "О"=>"%D0%9E", "п"=>"%D0%BF", "П"=>"%D0%9F", "р"=>"%D1%80", "Р"=>"%D0%A0",
                                  "с"=>"%D1%81", "С"=>"%D0%A1", "т"=>"%D1%82", "Т"=>"%D0%A2", "у"=>"%D1%83", "У"=>"%D0%A3",
                                  "ф"=>"%D1%84", "Ф"=>"%D0%A4", "х"=>"%D1%85", "Х"=>"%D0%A5", "ц"=>"%D1%86", "Ц"=>"%D0%A6",
                                  "ч"=>"%D1%87", "Ч"=>"%D0%A7", "ш"=>"%D1%88", "Ш"=>"%D0%A8", "щ"=>"%D1%89", "Щ"=>"%D0%A9",
                                  "ъ"=>"%D1%8A", "Ъ"=>"%D0%AA", "ы"=>"%D1%8B", "Ы"=>"%D0%AB", "ь"=>"%D1%8C", "Ь"=>"%D0%AC",
                                  "э"=>"%D1%8D", "Э"=>"%D0%AD", "ю"=>"%D1%8E", "Ю"=>"%D0%AE", "я"=>"%D1%8F", "Я"=>"%D0%AF",
                                  "і"=>"%D1%96", "І"=>"%D0%86", "ї"=>"%D1%97", "Ї"=>"%D0%87", "є"=>"%D1%94", "Є"=>"%D0%84"));
        $url = urlencode($url);

//        $url = preg_replace('!^https?://!i','',$url); // рєгулярка для http:// b https://
//        $url = urlencode($url);
//        $url = str_replace('%2F','/',$url);
//        $url = str_replace('%3A',':',$url);
//        $url = str_replace('%3F','/',$url);
//        $url = str_replace('%26','&',$url);
//        $url = str_replace('%3D','=',$url);
//        $url = 'http://' . $url;
//        Controller::ddd($url);
        return $url;
        // проверяем, если кириллический домен, то конвертируем его
        // if (preg_match('/[а-яА-Я]/', $url)) {
    }

    // создание скриншота
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
        $url = self::url_encode($url);
        $shell = ROOT.'/webroot/js/phantomjs '.ROOT.'/webroot/js/rasterize.js '.$url.' '.ROOT.'/webroot/uploads/preview/'.$file_name.'.jpg'.' '.'400px*250px 0.3125';
        // запуск с ожиданием завершения (windows and linux)
        // $result = shell_exec($shell);
        // if (!$result || $result == ''){
        //     return false;
        // }else{
        //    return true;
        //}

        // запуск в фоне, без ожидания завершения
        pclose(popen("start /B ". $shell, "r")); // for windows
        // exec($shell . " > /dev/null &"); // for linux
        return true;
    }

    // определение заголовка страници
    public static function get_title($url){
//        $url = self::url_encode($url);

        // определение заголовка страници через PhantomJS
//        $shell = ROOT.'/webroot/js/phantomjs '.ROOT.'/webroot/js/title.js '.$url;
//        $title = shell_exec($shell);
//        if ($title === False || $title == '') {
//            $title = "Перевірте посилання";
//        }
//        else{
//            $title = mb_convert_encoding($title, "UTF-8");
//        }
//        return $title;

//        $content = file_get_contents($url);
//        $url = urldecode($url);
//        $content = self::curl_get_file_contents($url);
//        Controller::ddd($content);

//        $file = file($url);
//        $content = implode("",$file);

        include ROOT."/lib/include/simple_html_dom.php";
//        $url = urldecode($url);
        $content = file_get_html($url);
        $data = $content->find('title');
        $title = $data[0]->innertext;
        $title = mb_convert_encoding($title, "UTF-8");

        $result =  mb_detect_encoding($title);
        return $result;

//        $content = false;
//        if ($content === False){
//            $title = "Перевірте посилання";
//        }
//        else{
//            preg_match_all("|<title(.*)>(.*)</title>|sUSi", $content, $matches);
//            $title = $matches[2][0];
//            $title = mb_convert_encoding($title, "UTF-8");
//        }
        return $title;
    }

    // удаление картинки
    public static function delete_image($file_name){
        $file_name = 'uploads'.DS.'preview'.DS.$file_name.'.jpg';
        // добавить if file_exist
        if (unlink ($file_name)){
            return true;
        }
        else{
            //пишем лог
        }
    }

}