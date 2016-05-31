<?
session_start(); //мювхмюел яеяяхч
if(!isset($_SESSION['admin'])){
    //оепедюер GET'НЛ оюпюлерп ref = рнлс юдпеяс я йнрнпнцн опхькх МЮ ЩРС ЯРПЮМХЖС
    header("Location: /admin/secure/login.php?ref={$_SERVER['REQUEST_URI']}");
    exit;
}