<?php

if($_GET['result'] === 'success') {
    echo '<p style="color: green;">Спасибо за заказ<p>';
} else {
    echo '<p style="color: red;">Ошибка<p>';
}