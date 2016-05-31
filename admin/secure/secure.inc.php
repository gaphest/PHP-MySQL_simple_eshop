<?
//���� � ������� ����� ��������� �����:��� ������:C���:���������� ��������
define('FILE_NAME', '.htpasswd');

//������� ���������� �������������� n ��� ������ (��������� �� ������ + ����)
function getHash($string, $salt, $iterationCount){
    for($i=0; $i< $iterationCount; $i++)
        $string= sha1($string . $salt);
    return $string;
}
//������� ���������� ������ � ������� ������ user,���,���� � ���� .htpasswd
function saveHash($user, $hash, $salt, $iteration){
    $str= "$user:$hash:$salt:$iteration\n";
    if(file_put_contents(FILE_NAME, $str, FILE_APPEND))
        return true;
    else
        return false;
}


//��������� ������� (���� $user:$hash:$salt:$iteration) � ����� .htpasswd
// ���� �� � ��� ����� $user == $login, ���� ������� ����� ������� �� ���������� ��
function userExists($login){
    //���� ����� .htpasswd ��� �� ���������� false
    if(!is_file(FILE_NAME))
        return false;
    //���� ���� ���������� ��� � ������ $users
    $users=file(FILE_NAME);

    foreach ($users as $user){
        //��� ������� �������� ������� users ���� "$user:$hash:$salt:$iteration";
        //���� � $user:$hash:$salt:$iteration ���� $login
        //�� ���������� ���� $user:$hash:$salt:$iteration
        if(strpos($user, $login)!==false)
        return $user;
    }
    return false;
}

//���������� ������ � �������������� � login.php
function logOut(){
    session_destroy();
    header('Location: secure/login.php');
    exit;
}