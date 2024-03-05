<?php

class DataFromSun {

    public function getData() {
        $sunData = file_get_contents('php://input');

        if(empty($sunData)) {
            HU::log('Пришёл пустой запрос');
            die;
        }

        parse_str($sunData, $sunDataJson);
        HU::log('Данные, которые пришли из Sun');
        HU::log($sunDataJson);

        $this->price = $sunDataJson['leads']['sun'][0]['price'];
        $this->leadId = $sunDataJson['leads']['sun'][0]['id'];

        return $sunDataJson;
    }

}


class DataFromCbr {

    public function getCurrentDate() {
        $currentDate = new DateTime();
        $currentDate->setTimezone(new DateTimeZone('Europe/Moscow'));

        $currentCbrDate = $currentDate->format('d/m/Y');
        $currentFileDate = $currentDate->format('d-m-Y');

        $this->currentCbrDate = $currentCbrDate;
        $this->currentFileDate = $currentFileDate;
    }

    public function getData() {

        if(file_exists('quotes_' . "$this->currentFileDate" . '.xml')) {
            HU::log("Уже подгружены данные о котировках за сегодня $this->currentFileDate. Подгрузка новых не требуется");
        } else {

            try {
                HU::log('Делаем запрос курсов ЦБР');

                $curl = curl_init(URL_CBR . $this->currentCbrDate);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_HEADER, false);
                $response = curl_exec($curl);
                curl_close($curl);
                
                $response = iconv('Windows-1251', 'UTF-8', $response);
                $response = str_replace('windows-1251', 'UTF-8', $response);

                $this->writeXml($response);

            } catch(Exception $e) {
                HU::log("Ошибка запроса данных из Центробанка. Текст ошибки: ".$e->getMessage());
                return false;
            }

        }

    }

    public function writeXml($response) {
        array_map("unlink", glob("quotes_*.xml"));

        file_put_contents('quotes_' . "$this->currentFileDate" . '.xml', $response);
        HU::log("Записаны новые данные о котировках за сегодня $this->currentFileDate");
    }

}


class RateCurrencies {

    public function getRateFromFile($currentFileDate, $currency) {

        if(file_exists('quotes_' . "$currentFileDate" . '.xml')) {
            $arrXml = simplexml_load_file('quotes_' . "$currentFileDate" . '.xml');
            HU::log('Получены данные из файла xml');
            HU::log($arrXml);

            foreach($arrXml as $arrCurrency) {
                if(isset($arrCurrency->CharCode)) {
                    if($arrCurrency->CharCode == CURRENCIES_CHAR_CODE[$currency]) {
                        if($arrCurrency->Nominal == 1) {
                            $this->rateCurrency = str_replace(',','.',reset($arrCurrency->Value));
                        } else {
                            $this->rateCurrency = str_replace(',','.',reset($arrCurrency->Value)) / str_replace(',','.',reset($arrCurrency->Nominal));
                        }
                    }
                }
            }

            if(!isset($this->rateCurrency)) {
                HU::log("Валюта $currency не найдена в котировках. Скрипт завершает работу");
                die;
            }

            HU::log("На дату $currentFileDate курс валюты $currency составляет $this->rateCurrency руб. за единицу");

        } else {
            HU::log("Не удалось прочитать файл xml");
            die;
        }
    }

    public function getTypeOfCurrency($api, $leadId) {

        HU::log("Получаем сделку id $leadId по апи");
        try {
            sleep(1);
            $res = $api->lead->getById($leadId);
        } catch (Exception $e) {
            HU::log('Не удалось получить сделку: ', $e->getMessage());
    
            try {
                sleep(3);
                HU::log("Повторно получаем сделку $leadId по апи");
    
                $res = $api->contact->getById($leadId);
            } catch (Exception $e) {
                HU::log('Снова не удалось получить сделку. Скрипт завершает работу ', $e->getMessage());
                die;
            }
    
        }

        $this->typeOfCurrency = HAmo::getCFValue($res['result'], TYPE_OF_CURRENCY_CF_ID);

        if(!isset($this->typeOfCurrency) || $this->typeOfCurrency == '') {
            HU::log("Не указан тип валюты в кастомном поле сделки. Скрипт завершает работу");
            die;
        }

    }


    function writeCurrencyBudgetInLead($api, $leadId, $budgetCfId, $rateCurrency, $price) {

        HU::log('Обогащаем сделку');

        $data = array(
            'id'            => $leadId,
            'custom_fields' => [
                $budgetCfId => ceil($price / $rateCurrency * 1.1)
            ],
        );
        HU::log('data');
        HU::log($data);

        try {
            sleep(1);
            $result = $api->lead->update($data);
            HU::log($result);
        } catch (Exception $e) {
            HU::log('Не удалось обогатить сделку');
            $e->getMessage();

            try {
                sleep(3);
                HU::log('Повторно пытаемся обогатить сделку');
                $result = $api->lead->update($data);
                HU::log($result);
            } catch (Exception $e) {
                HU::log('Снова не удалось обогатить сделку. Скрипт завершает работу');
                $e->getMessage();
                die;
            }
            
        }

        if(!isset($result['result']['leads']['update']['errors'])) {
            HU::log("Сделка id $leadId успешно обогащена");
        } else {
            HU::log("Что-то пошло не так с обогащением сделки");
        }

    }

}


