<?
//тюик б йнрнпнл асдср упюмхрэяъ кнцхм:ущь оюпнкъ:Cнкэ:йнкхвеярбн хрепюжхи
define('FILE_NAME', '.htpasswd');

//тсмйжхъ бнгбпюыюер гюуеьхпнбюммсч n ПЮГ ярпнйс (янярнъысч хг ярпнйх + янкэ)
function getHash($string, $salt, $iterationCount){
    for($i=0; $i< $iterationCount; $i++)
        $string= sha1($string . $salt);
    return $string;
}
//тсмйжхъ гюохяшбюер ярпнйс б ЙНРНПСЧ БУНДХР user,ущь,ЯНКЭ б тюик .htpasswd
function saveHash($user, $hash, $salt, $iteration){
    $str= "$user:$hash:$salt:$iteration\n";
    if(file_put_contents(FILE_NAME, $str, FILE_APPEND))
        return true;
    else
        return false;
}


//опнбепъер ярпнвйх (БХДЮ $user:$hash:$salt:$iteration) б тюике .htpasswd
// ЕЯРЭ КХ Б МХУ ВРНАШ $user == $login, еякх мюундхр рюйсч ярпнвйс рн бнгбпюыюер ее
function userExists($login){
    //еякх тюикю .htpasswd мер РН БНГБПЮЫЮЕР false
    if(!is_file(FILE_NAME))
        return false;
    //еякх еярэ гювхршбюер ецн б люяяхб $users
    $users=file(FILE_NAME);

    foreach ($users as $user){
        //дкъ ЙЮФДНЦН ЩКЕЛЕМРЮ ЛЮЯЯХБЮ users БХДЮ "$user:$hash:$salt:$iteration";
        //еякх Б $user:$hash:$salt:$iteration ЕЯРЭ $login
        //РН БНГБПЮЫЮЕР ЩРНР $user:$hash:$salt:$iteration
        if(strpos($user, $login)!==false)
        return $user;
    }
    return false;
}

//смхврнфюер яеяяхч х оепемюопюбкъер Б login.php
function logOut(){
    session_destroy();
    header('Location: secure/login.php');
    exit;
}