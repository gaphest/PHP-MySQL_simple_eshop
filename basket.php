<?php
	// подключение библиотек
	require "inc/lib.inc.php";
	require "inc/db.inc.php";
?>
<html>
<head>
	<title>Корзина пользователя</title>
</head>
<body>
	<h1>Ваша корзина</h1>
<?php
$goods=myBasket();
if(!is_array($goods)){
	echo "Произошла ошибка при выводе товаров";
	exit;
}
if($goods)
	echo '<p> Вернуться в <a href="catalog.php">каталог</a></p>';
else{
	echo "<p>Корзина пуста. Вернитесь в <a href='catalog.php'> Каталог </a></p>";
}
?>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
<tr>
	<th>N п/п</th>
	<th>Название</th>
	<th>Автор</th>
	<th>Год издания</th>
	<th>Цена, руб.</th>
	<th>Количество</th>
	<th>Удалить</th>
</tr>
<?php
	$i=1; //Для порядкового номера товара в корзине
	$sum=0; //ДЛЯ СУММЫ ТОВАРОВ
	//Формируем HTML-ТАБЛИЦУ НА СТРАНИЦЕ С ДАННЫМИ ИЗ БАЗЫ ДАННЫХ
	foreach($goods as $item){
		?>
		<tr>
			<td><?=$i?></td>
			<td><?=$item['title']?></td>
			<td><?=$item['author']?></td>
			<td><?=$item['pubyear']?></td>
			<td><?=$item['price']?></td>
			<td><?=$item['quantity']?></td>
			<td><a href="delete_from_basket.php?id=<?=$item['id']?>"> Удалить </a> </td>
			<!--Ссылка "Удалить" передаст методом GET параметр id = $item['id']-->
		</tr>
		<?
		$i++;
		$sum += $item['price']*$item['quantity'];
	}
	?>

</table>

<p>Всего товаров в корзине на сумму: <?=$sum?>руб.</p>

<div align="center">
	<input type="button" value="Оформить заказ!"
                      onClick="location.href='orderform.php'" />
</div>

</body>
</html>







