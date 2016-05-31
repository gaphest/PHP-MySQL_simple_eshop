<?
require_once "secure/session.inc.php";
//Начинает сессию
//ЕСЛИ НЕТУ $_SESSION[admin] перенаправляет в login.php
//и ПЕРЕДАЕТ GET'ом ПАРАМЕТР ref = ТОМУ АДРЕСУ С КОТОРОГО ПРИШЛИ на эту страницу

require_once "secure/secure.inc.php";

//ЕСЛИ БЫЛО НАЖАТО <li><a href='index.php?logout'>Завершить сеанс</a></li> в форме
if(isset($_GET['logout'])){
    //Дергает logout() из secure.php
    //СЕССИЯ УНИЧТОЖАЕТСЯ и перенаправляемся на login.php
    logOut();
}
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Админка</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
</head>
<body>
	<h1>Администрирование магазина</h1>
	<h3>Доступные действия:</h3>
	<ul>
		<li><a href='add2cat.php'>Добавление товара в каталог</a></li>
		<li><a href='orders.php'>Просмотр готовых заказов</a></li>
		<li><a href='secure/create_user.php'>Добавить пользователя</a></li>
		<li><a href='index.php?logout'>Завершить сеанс</a></li>
	</ul>
</body>
</html>