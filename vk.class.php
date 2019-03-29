<?php
//Задаём класс
class VK {
  
    public $token = ''; //Создаём публичную переменную для токена, который нужно отправлять каждый раз при использовании апи вк
  
    public function __construct($token) {
        $this->token = $token; //Забиваем в переменную токен при конструкте класса
    }
     
    public function PhotoUploadServer($group_id) {
        //Заполняем массив $data инфой, которую мы через api отправим до вк. О функции api "getOwnerCoverPhotoUploadServer" можно почитать в официальной документации вк
        $data = array( 
            'crop_x2' => '1590',
            'crop_y2' => '400',
            'group_id'      => $group_id,
            'v'            => '5.71', //Версия API VK. Узнать нужную можно через официальную документацию вк
        );
        //Получаем ответ через функцию отправки до апи, которую создадим ниже
        $out = $this->request('https://api.vk.com/method/photos.getOwnerCoverPhotoUploadServer', $data);
        //И пусть функция вернёт ответ
        return $out['response'];
    }
     
    public function UploadPhoto($url, $file) {
        $data = array( 
            'photo'      => new CURLFile($file), //Отправляем нашу обложку на сервера вк
        );
        //Получаем ответ через функцию отправки до апи, которую создадим ниже
        $out = $this->request($url, $data);
        //И пусть функция вернёт ответ
		
        return $out;
    }
     
    public function SavePhoto($hash, $photo) {
        $data = array( 
            'hash'       => $hash,
            'photo'      => $photo,
            'v'            => '5.71', //Версия API VK. Узнать нужную можно через официальную документацию вк
        );
        //Получаем ответ через функцию отправки до апи, которую создадим ниже
        $out = $this->request('https://api.vk.com/method/photos.saveOwnerCoverPhoto', $data);
        //И пусть функция вернёт ответ
        return $out;
    }
      
    public function request($url, $data = array()) {
        $curl = curl_init(); //мутим курл-мурл в переменную. Для отправки предпочтительнее использовать курл, но можно и через file_get_contents если сервер не поддерживает
          
        $data['access_token'] = $this->token; //токен, который нужно отправить вместе с запросом тоже нужно добавить в дату
        curl_setopt($curl, CURLOPT_URL, $url); //Сюда забивается ссылка, куда отправить $data
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST'); //Отправляем через POST
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); //Сами данные отправляемые
        file_put_contents('log.txt', '1'.curl_error($curl), FILE_APPEND);  
        $out = json_decode(curl_exec($curl), true); //Получаем результат выполнения, который сразу расшифровываем из JSON'a в массив для удобства
        curl_close($curl); //Закрываем курл
        return $out; //Отправляем ответ в виде массива
    }
}