<?php
	// подключение библиотек
	require "secure/session.inc.php";
	require "../inc/lib.inc.php";
	require "../inc/db.inc.php";
//ПРИНИМАЕМ ДАННЫЕ ИЗ ФОРМЫ В add2cat.php
$title=ClearStr($_POST['title']);
$author=ClearStr($_POST['author']);
$pubyear=ClearInt($_POST['pubyear']);
$price=ClearInt($_POST['price']);

//ДОБАВЛЯЕМ В ТАБЛИЦУ CATALOG В БАЗЕ ДАННЫХ
if(!addItemTocatalog($title, $author, $pubyear, $price)){
	//ЕСЛИ ФУНКЦИЯ ВЕРНУЛА FALSE
	echo "Произошла ошибка при добавлении товара";
}
	else{
		header('Location:add2cat.php');
		exit;
		//ВОЗВРАЩАЕМСЯ К ФОРМЕ
	}
?>