<?php
	// ����������� ���������
	require "inc/lib.inc.php";
	require "inc/db.inc.php";

//��������� � $id (����� ���� ����� � �������� "� �������" ����� ��� ������� id
$id=ClearInt($_GET['id']);
$quantity=1;
add2Basket($id,$quantity);
header('Location:catalog.php');
exit;
?>