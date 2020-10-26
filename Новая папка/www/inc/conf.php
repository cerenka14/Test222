<?php
// Разработчик: http://webupper.ru
// Поддержка скрипта: http://webupper.ru/contacts

//$ip = "0.0.0.0";
//if ($_SERVER["REMOTE_ADDR"] != $ip) { exit($_SERVER["REMOTE_ADDR"]); }

ob_start();
define('SITE', 'fishtimer.com'); // Адрес сайта без http://
define('SITENAME', 'Ставки на рыбу'); // Название сайта
define('DB_HOST', 'localhost');
define('DB_USER', 'user'); // Имя пользователя
define('DB_PASS', 'pass'); // Пароль
define('DB_BASE', 'base'); // Имя базы данных
define('DB_CHARSET', 'utf8');
$connect_db = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_BASE) or die('Ошибка подключения: '.mysqli_connect_error());
mysqli_set_charset ($connect_db, DB_CHARSET) or die('Кодировка не установлена');
if(isset($_GET["q"])) { setcookie ("referer", $_GET['q'], time()+86400, "/", SITE); }

//Настройки скрипта
$toadmin = 0.1; //Процент админу
$toref = 0.05; //Процент рефереру

//Payeer
$p_shop_id = '123'; //id магазина
$p_key = 'dsfsdfsdf'; //Секретный ключ
$p_out_number = 'P1123123'; //Номер Паер аккаунта
$p_out_id = '1231231'; //id выплат
$p_out_key = 'fgdfgdfg'; //Секретный ключ для выплат
?>