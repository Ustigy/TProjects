<?php

define('IS_TEST', false);

if (IS_TEST) {
    define('API_KEY', '123123123');
    define('URL_CBR', 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=');

    define('TYPE_OF_CURRENCY_CF_ID', 1528079);

    define('CURRENCIES_CHAR_CODE', [
        'Доллар' => 'USD',
        'Евро' => 'EUR',
        'Тенге' => 'KZT'
    ]);

    define('BUDGET_IN_CURRENCIES_CF_ID', [
        'Доллар' => 1528081,
        'Евро' => 1528083,
        'Тенге' => 1528085
    ]);

} else {
    define('API_KEY', '234234234');
    define('URL_CBR', 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=');

    define('TYPE_OF_CURRENCY_CF_ID', 1967622);

    define('CURRENCIES_CHAR_CODE', [
        'Доллар' => 'USD',
        'Евро' => 'EUR',
        'Тенге' => 'KZT'
    ]);

    define('BUDGET_IN_CURRENCIES_CF_ID', [
        'Доллар' => 1967626,
        'Евро' => 1967624,
        'Тенге' => 1967628
    ]);
}