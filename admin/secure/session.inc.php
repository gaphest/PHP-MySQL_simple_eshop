<?
session_start(); //�������� ������
if(!isset($_SESSION['admin'])){
    //�������� GET'�� �������� ref = ���� ������ � �������� ������ �� ��� ��������
    header("Location: /admin/secure/login.php?ref={$_SERVER['REQUEST_URI']}");
    exit;
}