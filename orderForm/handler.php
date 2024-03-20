<?php

require_once('./helpers.php');
setupLog();
require_once('./config.php');
require_once('./functions.php');


writeLog($_POST);

$name = $_POST['name'];
$phone = $_POST['phone'];
$hidden_field = $_POST['hidden_field'];

if(!$phone || !$name) {
    writeLog('Запрос без указания телефона или имени');
    fail();
}

$connection = connectToDb(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
createTable($connection, TABLE_NAME);
$phone_already_exist = searchPhoneInDb($connection, $phone, TABLE_NAME);

if($phone_already_exist) {
    writeLog("Уже существует заказ с телефоном $phone");
    fail();
} else {
    writeLog("Записываем телефон $phone в БД");
    insertIntoDb($connection, $phone, TABLE_NAME);
}

writeLog('Отправляем данные о заказе');
$uuid = sendData($name, $phone, $hidden_field, ORDERS_URL)['uuid'] ?? '';

if($uuid) {
    success();
} else {
    fail();
}

