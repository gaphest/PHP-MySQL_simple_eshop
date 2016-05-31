<?php
	require "inc/lib.inc.php";
	require "inc/db.inc.php";
// ПРИНИМАЕМ ВВЕДЕНЫЕ ЮЗЕРОМ ДАННЫЕ ИЗ ФОРМЫ ORDERFORM
$n=ClearStr($_POST['name']);
$p=ClearStr($_POST['phone']);
$e=ClearStr($_POST['email']);
$a=ClearStr($_POST['address']);
// $oid -ИДЕНТИФИКАТОР ЗАКАЗА - берем из $basket
$oid=$basket['orderid'];
// ТЕКУЩЕЕ ВРЕМЯ
$dt=time();
//ФОРМИРУЕМ СТРОЧКУ ИЗ ВЫШЕУКАЗАННЫХ ПЕРЕМЕННЫХ
$order= "$n|$e|$p|$a|$oid|$dt\n";
//ЗАПИСЫВАЕМ ЭТУ СТРОЧКУ В ФАЙЛ
file_put_contents('admin/'.ORDERS_LOG, $order, FILE_APPEND);
saveOrder($dt);
?>

<html>
<head>
	<title>Сохранение данных заказа</title>
</head>
<body>
	<p>Ваш заказ принят.</p>
	<p><a href="catalog.php">Вернуться в каталог товаров</a></p>
</body>
</html>