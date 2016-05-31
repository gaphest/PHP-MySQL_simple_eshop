<?php
include_once "db.inc.php";
//ФУНКЦИЯ ДЛЯ ОБРАБОТКИ ЧИСЛОВЫХ ПОЛОЖИТЕЛЬНЫХ ЗНАЧЕНИЙ
function ClearInt($data){
    return abs((int)$data);
}
//ФУНКЦИЯ ДЛЯ ОБРАБОТКИ СТРОКОВЫХ ЗНАЧЕНИЙ
function ClearStr($data){
    global $link;
    return mysqli_real_escape_string($link, trim(strip_tags($data)));
}
//Создает Куку basket в качестве значения МАССИВ BASKET
function saveBasket(){
    global $basket;
    $basket=base64_encode(serialize($basket));
    setcookie('basket', $basket,0x7FFFFFFF);
}
//ЕСЛИ У ЮЗЕРА НЕТУ КУКИ BASKET (Т.Е ЗАШЕЛ ПЕРВЫЙ РАЗ СОЗДАЕТ КУКУ BASKET СО ЗНАЧЕНИЕМ orderid (сгенерированный idшник)
//ЕСЛИ ЖЕ КУКА ЕСТЬ (НЕ ПЕРВЫЙ РАЗ) СЧИТАЕМ КОЛ-ВО ТОВАРОВ(ЭЛЕМЕНТЫ МАССИВА -1), -1 т.к ПЕРВЫЙ ЭЛЕМЕНТ -orderid
function basketInit(){
    global $basket, $count;
    if(!isset($_COOKIE['basket'])){
        $basket=array('orderid'=> uniqid());
        saveBasket();
    }
    else{
        $basket=unserialize(base64_decode($_COOKIE['basket']));
        $count=count($basket)-1;
    }
}
//УДАЛИТ ИЗ КУКИ и ИЗ $basket ЭЛЕМЕНТ С $id
function deleteItemFromBasket($id){
    global $basket;
    unset($basket[$id]);
    saveBasket();
}

//ФУНКЦИЯ ДОБАВЛЕНИЯ ТОВАРА В КОРЗИНУ
function add2Basket($id, $q){
    global $basket;
    $basket[$id]=$q;
    saveBasket();
}

//ФУНКЦИЯ ВЫБОРКИ ДАННЫХ ДЛЯ КОРЗИНЫ ИЗ catalogа В ФОРМЕ Array
function myBasket(){
    global $link, $basket;
    $goods=array_keys($basket);//ВЫБРАЛ ВСЕ КЛЮЧИ
    array_shift($goods); //ИЗВЛЕКАЕМ ПЕРВЫЙ ЭЛЕМЕНТ МАССИВА(т.е orderid)
    if(!count($goods))
        return array(); //ЕСЛИ $goods ПУСТОЙ(!сount) ТО ФУНКЦИЯ ВЕРНЕТ ПУСТОЙ МАССИВ
    $ids = implode (",", $goods); //ПОЛУЧАЕМ В СТРОКУ ЧЕРЕЗ ЗАПЯТЫЕ ВСЕ ID ХРАНИВШИЕСЯ В $basket
    $sql="SELECT id, title, author, pubyear, price FROM catalog
            WHERE id IN($ids)"; // ВСТАВЛЯЕМ СТРОКУ $ids В КАЧЕСТВЕ УСЛОВИЯ ДЛЯ ЗАПРОСА
    if(!$result=mysqli_query($link,$sql))
        return false;
    $items=result2Array($result); //ВЫЗОВ ФУНКЦИИ result2Array/ ВЕРНЕТСЯ МАССИВ
    mysqli_free_result($result);
    return $items;
}
// ПРЕВРАЩАЕТ ДАННЫЕ ИЗ $resulta В МАССИВ $arr
function result2Array($data){
    global $basket;
    $arr = array();
    while($row = mysqli_fetch_assoc($data)){
        $row['quantity']=$basket[$row['id']]; // ПОЛУЧАЕТ КОЛИЧЕСТВО ДЛЯ ЭТОГО id
        $arr[]=$row;
    }
    return $arr;
}

// ФУНКЦИЯ ДОБАВЛЕНИЯ В ТАБЛИЦУ CATALOG В БАЗЕ ДАННЫХ
function addItemTocatalog($title, $author, $pubyear, $price){
    global $link;
    $sql="INSERT INTO catalog(title, author, pubyear, price) VALUES (?, ?, ?, ?)";
    if(!$stmt=mysqli_prepare($link, $sql))
        return false; // ЕСЛИ В STMT ВЕРНЕТСЯ FALSE ТО ФУНКЦИЯ AddItemToCatalog ВЕРНЕТ FALSE
    mysqli_stmt_bind_param($stmt, 'ssii', $title, $author, $pubyear, $price);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return true;
}
//ФУНКЦИЯ ДОБАВЛЕНИЯ В  ЗАКАЗЫ ИЗ КОРЗИНЫ
function saveOrder($dt){
    global $link, $basket;
    //ПОЛУЧАЕМ ВСЁ СОДЕРЖИМОЕ КОРЗИНЫ
    $goods=myBasket();
    //ВСТАВЛЯЕМ В ТАБЛИЦУ ORDERS ДАННЫЕ ИЗ КОРЗИНЫ
    $stmt= mysqli_stmt_init($link);
    $sql= 'INSERT INTO orders(title, author, pubyear, price, quantity, orderid, datetime)
                          VALUES(?,?,?,?,?,?,?)';
    if(!mysqli_stmt_prepare($stmt,$sql))
        return false;
    //ВЫПОЛНЯЕТ SQL ЗАПРОС ДЛЯ КАЖДОГО ЭЛЕМЕНТА В КОРЗИНЕ
    foreach($goods as $item){
        mysqli_stmt_bind_param($stmt,'ssiiisi', $item['title'], $item['author'],$item['pubyear'],
            $item['price'],$item['quantity'],$basket['orderid'],$dt); //orderid из $basket а не $item
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
    setcookie('basket','',time()-3600); //ПОСЛЕ ДОБАВЛЕНИЯ В ORDERS, УДАЛЯЕТ КУКУ BASKET
    return true;
}

function getOrders(){
    global $link;
    if(!is_file(ORDERS_LOG))
        return false; //Если файла orders.log нету то функция вернет false
    $orders=file(ORDERS_LOG);// Зачитываем в файл в массив(т.к file()) $orders
    $allorders=array();//инициализируем массив allorders
    foreach($orders as $order){
        list($n,$e,$p,$a,$oid,$dt)=explode('|', $order); // РАЗБИВАЕМ СТРОЧКУ В $orders(т.е по сути
        //содержимое orders.log и записываем в переменные
        $orderinfo=array();//инициализируем массив orderinfo
        //Вставляем переменные в массив orderinfo
        $orderinfo['name']=$n;
        $orderinfo['email']=$e;
        $orderinfo['phone']=$p;
        $orderinfo['address']=$a;
        $orderinfo['orderid']=$oid;
        $orderinfo['dt']=$dt;
        //БЕРЕМ ДАННЫЕ ИЗ SQL таблицы ORDERS
        $sql="SELECT title, author, pubyear, price, quantity from ORDERS WHERE orderid='$oid'";
        if(!$result=mysqli_query($link, $sql))
            return false; //ЕСЛИ в результате sql запроса ниче не получилось
        $items=mysqli_fetch_all($result,MYSQLI_ASSOC);// Присваиваем построчно в массив items
        mysqli_free_result($result);
        $orderinfo['goods']=$items;//Запихиваем в orderinfo['goods'] описание товара
        $allorders[]=$orderinfo;
    }
    return $allorders;
}

//ФУНКЦИЯ ДЛЯ ФОРМИРОВАНИЯ МАССИВА С ДАННЫМИ ИЗ ТАБЛИЦЫ Catalog В БАЗЕ ДАННЫХ
function SelectAllItems(){
    global $link;
    $sql='SELECT id, title, author, pubyear, price FROM catalog';
    if(!$result=mysqli_query($link, $sql))
        return false;
    $items=mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    return $items;
}