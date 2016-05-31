<?php
	// подключение библиотек
	require "inc/lib.inc.php";
	require "inc/db.inc.php";
//ПРИНИМАЕМ $id КОТОРЫЙ БЫЛ ПЕРЕДАН ГЕТом КОГДА ЮЗЕР В КОРЗИНЕ НАЖАЛ УДАЛИТЬ
$id=clearInt($_GET['id']);

if($id){ //ЕСЛИ $id != 0 тк вручную могут злоумышленник может передать что-нибудь нехорошее
	deleteItemFromBasket($id);
	header('Location: basket.php');//Перенаправимся в корзину
	exit;
}
	
?>