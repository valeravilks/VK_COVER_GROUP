<?php
 
    $current_date = date('d' . '.' . 'm' . '.' . 'Y') . 'г'; //Получаем в переменную текущую дату
	$minutes = date('G' . ':' . 'i'); //Получаем текущее время
    $image = imageCreateFromJpeg(__DIR__.'cover.jpg'); //получаем изображение, обложку из файла
    $white = imagecolorallocate($image, 255, 255, 255); //Добавляем в палитру белый цвет для шрифта
     
    $font = __DIR__ . '/ProximaNova-Regular.ttf'; //Шрифт
    $center = 1435; // Данная переменная - координата х середины блока, по которому нужно центровать текст
    $ttf_box = imagettfbbox(25, 0, $font, $current_date); //ширина даты
	$ttf_box2 = imagettfbbox(85, 0, $font, $minutes); //ширина времени
    //В строчке выше
    //25 или 85 - это размер текста
    //0 - это угол наклона текста
    //Ну и последние параметры это шрифт и текст
    $position = $center - round(($ttf_box[2]-$ttf_box[0])/2); //Место, откуда нужно будет начать писать текст чтобы тот оказался посередине
	$position2 = $center - round(($ttf_box2[2]-$ttf_box2[0])/2); // Аналогично для второго текста
 
   //Пишем тот самый текст
    imagefttext($image, 25, 0, $position, 175, $white, $font, $current_date);
	imagefttext($image, 85, 0, $position2, 126, $white, $font, $minutes);
    imagejpeg($image, 'cover_actual.jpg', 100); //Сохраняем полученную картинку в cover_actual.jpg в 100% качестве
    imagedestroy($image); //Освобождаем
 
    include_once ('vk.class.php'); //Подключаем наш vk.class.php
     
    //Сюда пишем ключ апи созданный для группы в вк
    $vk = new vk('ххххххххххххххххххххххххххххх');
     
    $url = $vk->PhotoUploadServer('хххххххх'); //Вставляем ид группы в эту функцию
	echo $url['upload_url'] . '<br>';
    $photo = $vk->UploadPhoto($url['upload_url'], 'cover_actual.jpg'); //Вставляем изображение, которое нужно отправить
	print_r($photo);
    $result = $vk->SavePhoto($photo['hash'], $photo['photo']); //Вставляем в обложку загруженную картинку
    echo "ok";
	
	
