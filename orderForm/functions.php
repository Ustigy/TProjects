<?php

function connectToDb($db_host, $db_user, $db_password, $db_name) {
    try {
        $connection = new mysqli($db_host, $db_user, $db_password, $db_name);
    } catch (Exception $e) {
        writeLog('Не удалось подключиться к БД');
        writeLog($e->getMessage());
    }

    return $connection;
}

function createTable($connection, $table_name) {
    $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
        phone VARCHAR(16) NOT NULL
    );";

    $connection->query($sql);
}

function insertIntoDb($connection, $phone, $table_name) {
    $sql= "INSERT INTO {$table_name} (phone) VALUES ('{$phone}');";

    $connection->query($sql);
}

function searchPhoneInDb($connection, $phone, $table_name) {
    $sql ="SELECT COUNT(*) as cnt FROM {$table_name} WHERE phone = '{$phone}'";

    $res = mysqli_fetch_assoc($connection->query($sql))['cnt'];
    return $res;
}

function fail() {
    $url = 'http://culturemedia/result.php?result=fail';
    $code = 303;

    header('Location: ' . $url, true, $code);
    exit();
}

function success() {
    $url = 'http://culturemedia/result.php?result=success';
    $code = 303;

    header('Location: ' . $url, true, $code);
    exit();
}

function sendData($name, $phone, $hidden_field, $url) {
    $data = [
        'stream_code' => 'iu244',
        'client'      => [
            'name'      => $name,
            'phone'     => $phone,
        ],
        'sub1'        => $hidden_field
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization: Bearer NWJLZGEWOWETNTGZMS00MZK4LWFIZJUTNJVMOTG0NJQXOTI3'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE)); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $res = curl_exec($ch);
    curl_close($ch);
    
    $res = json_decode($res, true);
    
    writeLog($res);
    return $res;
}
