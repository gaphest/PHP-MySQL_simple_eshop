<?php
	// подключение библиотек
	require "inc/lib.inc.php";
	require "inc/db.inc.php";

//ПРИНИМАЕМ в $id (КОГДА ЮЗЕР НАЖАЛ В КАТАЛОГЕ "В КОРЗИНУ" ГЕТом был передан id
$id=ClearInt($_GET['id']);
$quantity=1;
add2Basket($id,$quantity);
header('Location:catalog.php');
exit;
?>