<?php

$is_set = false;

if($is_set) {
    define('ORDERS_URL', 'https://webhook.site/61a0b739-4575-4b86-be79-1b5e5e743fec');
} else {
    define('ORDERS_URL', 'https://order.drcash.sh/v1/order');
}

define('DB_HOST', 'localhost');
define('DB_NAME', 'test');
define('DB_USER', 'dev');
define('DB_PASSWORD', '123');
define('TABLE_NAME', 'culturemedia_order_phones');