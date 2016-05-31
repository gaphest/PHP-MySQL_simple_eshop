<?
//require_once "session.inc.php";
require_once "secure.inc.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Хеширование SHA-1</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
</head>

<body>
<h1>Хеширование SHA-1</h1>
<?
//ЗНАЧЕНИЯ ПО УМОЛЧАНИЮ В ФОРМЕ
$user = 'root';//логин
$string = '1234';//пароль
$salt = '';
$iterationCount = 100;
$result = '';

if (!$salt)
	//ЕСЛИ СОЛИ НЕТ ГЕНЕРИРУЕТ ее
	$salt = str_replace('=', '', base64_encode(md5(microtime() . '1FD37EAA5ED9425683326EA68DCD0E59')));

//ЕСЛИ БЫЛА ПОСЛАНА ФОРМА
if ($_SERVER['REQUEST_METHOD']=='POST'){
	$user = $_POST['user'] ?: $user;
	//ЕСЛИ В ФАЙЛЕ .htpasswd НЕТУ ЗАПИСИ С ТАКИМ ЛОГИНОМ(Т.Е ЕЩЕ НЕТУ ТАКОГО ЮЗЕРА)
	if(!userExists($user)){
		$string = $_POST['string'] ?: $string;
		$salt = $_POST['salt'] ?: $salt;
		$iterationCount = (int) $_POST['n'] ?: $iterationCount;
		//ПОЛУЧАЕМ ХЭШ СТРОКУ
		$result = getHash($string, $salt, $iterationCount);

		//СОХРАНЯЕМ "$user:$hash:$salt:$iteration" в файл .htpass
		if(saveHash($user, $result, $salt, $iterationCount))
			$result = 'Хеш '. $result. ' успешно добавлен в файл';
		else
			//ЕСЛИ МЕТОД СОХРАНЕНИЯ в .htpasswd вернул FALSE
			$result = 'При записи хеша '. $result. ' произошла ошибка';
	}else{
		//ЕСЛИ В ФАЙЛЕ .htpasswd УЖЕ ЕСТЬ  USER C ТАКИМ LOGINом
		$result = "Пользователь $user уже существует. Выберите другое имя.";
	}
}
?>
<h3><?= $result?></h3>
<form action="<?= $_SERVER['PHP_SELF']?>" method="post">
	<div>
		<label for="txtUser">Логин</label>
		<input id="txtUser" type="text" name="user" value="<?= $user?>" style="width:40em"/>
	</div>
	<div>
		<label for="txtString">Пароль</label>
		<input id="txtString" type="text" name="string" value="<?= $string?>" style="width:40em"/>
	</div>
	<div>
		<label for="txtSalt">Соль</label>
		<input id="txtSalt" type="text" name="salt" value="<?= $salt?>"  style="width:40em"/>
	</div>	
	<div>
		<label for="txtIterationCount">Число иттераций</label>
		<input id="txtIterationCount" type="text" name="n" value="<?= $iterationCount?>"  style="width:4em"/>
	</div>		
	<div>
		<button type="submit">Создать</button>
	</div>	
</form>
</body>
</html>