<?
session_start(); //НАЧИНАЕМ СЕССИЮ
header("HTTP/1.0 401 Unauthorized");
require_once "secure.inc.php";

//ЕСЛИ БЫЛА ПОСЛАНА ФОРМА то POSTом были передани логин и пароль
//И ЕСЛИ БЫЛ ?ref= ТО ОН ПЕРЕДАЕТСЯ GETом
//ТО ЕСТЬ ДВА МЕТОДА POST и GET
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = trim(strip_tags($_POST['user']));
    $pw = trim(strip_tags($_POST['pw']));
    //ПРИНИМАЕМ посланный GET'ом ref для того чтобы отправиться туда где прервали
    $ref = trim(strip_tags($_GET['ref']));
    //ЕСЛИ $ref НЕ БЫЛ ПЕРЕДАН
    if (!$ref)
        $ref = '/eshop/admin/';

    if ($user and $pw) {
        //ЕСЛИ ПОЛУЧИЛИ массив с содеражнием $user:$hash:$salt:$iteration для введенного логина
        if ($result = userExists($user)) {
            //РАЗБИВАЕМ СТРОКУ $user:$hash:$salt:$iteration и принимаем в параметры
            list($login, $password, $salt, $iteration) = explode(':', $result);
            //ЕСЛИ ПОСЛЕ ПРОПУСКА ЧЕРЕЗ функцию хеширования введенного юзером пароля($pw0
            //он совпадает с тем паролем что хранится в файле
            if (getHash($pw, $salt, $iteration) == $password) {
                //Создаем $_SESSION['admin'] = true;
                $_SESSION['admin'] = true;
                //ОТПРАВЛЯЕМСЯ ПО АДРЕСУ КОТОРЫЙ БЫЛ ПЕРЕДАН GETом в $ref
                header("Location: $ref");
                exit;
             //ЕСЛИ ПАРОЛЬ НЕ СОВПАЛ
            } else $title = 'Неправильный пароль';
        }
        //ЕСЛИ НЕ ПОЛУЧИЛИ массив с содеражнием $user:$hash:$salt:$iteration для введенного логина
        else
            $title = 'Неправильное имя пользователя';
    }
    //ЕСЛИ НЕ БЫЛИ ВВЕДЕНЫ ЛОГИН И ПАРОЛЬ
    else
        $title = 'Заполните все поля формы!';
}

$title = 'Авторизация';
$user = '';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Авторизация</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
</head>
<body>
<h1><?= $title ?></h1>

<!--ЗДЕСЬ action REQUEST_URI чтобы форма отправилась на тот же адресс с теми же GET параметрами
ТО ЕСТЬ ЕСЛИ МЫ ЗАШЛИ НА ФОРМУ и у нас АДРЕСНОЙ СТРОКЕ http://2course/eshop/admin/secure/login.php?ref=/eshop/admin/
ТО МЫ ?ref=/eshop/admin/ СОХРАНИМ
А ЕСЛИ БЫ ИСПОЛЬЗОВАЛИ ПРОСТО PHP_SELF ТО ЭТОТ ?ref=/eshop/admin/ МЫ БЫ ПОТЕРЯЛИ ТАК КАК ВЕРНУЛИСЬ БЫ ПРОСТО
В http://2course/eshop/admin/secure/login.php  БЕЗ  GETA (?ref=/eshop/admin/)-->
<form action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
    <div>
        <label for="txtUser">Логин</label>
        <input id="txtUser" type="text" name="user" value="<?= $user ?>"/>
    </div>
    <div>
        <label for="txtString">Пароль</label>
        <input id="txtString" type="text" name="pw"/>
    </div>
    <div>
        <button type="submit">Войти</button>
    </div>
</form>
<a href="..\..\catalog.php">В каталог товаров</a>
</body>
</html>

