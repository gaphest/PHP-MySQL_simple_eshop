<?php
	require "inc/lib.inc.php";
	require "inc/db.inc.php";
?>
<html>
<head>
	<title>Каталог товаров</title>
</head>
<body>
<p>Товаров в <a href="basket.php">корзине</a>: <?= $count?></p>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
<tr>
	<th>Название</th>
	<th>Автор</th>
	<th>Год издания</th>
	<th>Цена, руб.</th>
	<th>В корзину</th>
</tr>
<?php
$goods= SelectAllItems();
if(!is_array($goods)) {
	echo "Произошла ошибка при выводе товаров";
	exit;
}
if(!$goods){ //ЕСЛИ ЭТО МАССИВ, НО ОН ПУСТ
	echo "Товаров нету";
}
//Формируем HTML-ТАБЛИЦУ НА СТРАНИЦЕ С ДАННЫМИ ИЗ БАЗЫ ДАННЫХ
foreach($goods as $item){
?>
	<tr>
		<td><?=$item['title']?></td>
		<td><?=$item['author']?></td>
		<td><?=$item['pubyear']?></td>
		<td><?=$item['price']?></td>
		<td><a href="add2basket.php?id=<?=$item['id']?>">В корзину </a> </td>
		<!--Ссылка "в корзину" передаст методом GET параметр id = $item['id']-->
	</tr>
<?
}
?>
</table>
<br><a href="basket.php">Перейти в корзину</a><br>
<br><a href="admin/index.php">Перейти в админку</a>
</body>
</html>