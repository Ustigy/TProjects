<?php

ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/phpErrors__' . basename(__FILE__, '.php') . '.log');

require_once(__DIR__ . '/../../../lib/init.php');
require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/classes.php');

HU::logSetup(dirname(__FILE__) . '/logs/' . HFile::getFileNameByPath(__FILE__, true) . '.log');

HU::log("---------------------START---------------------");

$semaphore = sem_get(632513523701, 1, 0666, 1);

if(sem_acquire($semaphore, true) == false) {
  HU::log('Семафор занят. Завершаем работу');
  die();
}

Customer\Configuration::getDefaultConfiguration()->setApiKey('key', API_KEY);
$api = new Customer\ApiClient();

$dataFromSun = new DataFromSun();
$dataFromSun->getData();

$dataFromCbr = new DataFromCbr();
$dataFromCbr->getCurrentDate();
$dataFromCbr->getData();

$rateCurrencies = new RateCurrencies();
$rateCurrencies->getTypeOfCurrency($api, $dataFromSun->leadId);
$rateCurrencies->getRateFromFile($dataFromCbr->currentFileDate, $rateCurrencies->typeOfCurrency);
$rateCurrencies->writeCurrencyBudgetInLead($api, $dataFromSun->leadId, BUDGET_IN_CURRENCIES_CF_ID[$rateCurrencies->typeOfCurrency], $rateCurrencies->rateCurrency, $dataFromSun->price);

HU::log("---------------------END---------------------");