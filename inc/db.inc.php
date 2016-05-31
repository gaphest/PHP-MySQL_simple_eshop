<?php
header('Content-Type: text/html; charset=utf-8');
define ('DB_HOST', '127.0.0.1');
define ('DB_LOGIN', 'root');
define ('DB_PASSWORD', '');
define ('DB_NAME', 'eshop');
define('ORDERS_LOG', 'orders.log'); //КОНСТАНТА КУДА МЫ БУДЕМ ЗАПИСЫВАТЬ ДАННЫЕ ПОЛЬЗОВАТЕЛЕЙ
// КОРЗИНА ПОКУПАТЕЛЯ
$basket=array(); // ИНИЦИАЛИЗИРУЕМ МАССИВ ДЛЯ КОРЗИНЫ

$count=0;// КОЛ-ВО ТОВАРОВ В КОРЗИНЕ ПОКУПАТЕЛЯ
$link=mysqli_connect(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_NAME) or die(mysqli_connect_error());//устанавливаем соединение
//ВЫЗОВ ФУНКЦИИ BASKETINIT() из lib.inc
basketInit();

