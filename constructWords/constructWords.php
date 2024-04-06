<?php

declare(strict_types=1);
require_once('./helpers.php');
setupLog();

define('URL_WORD_BASE', 'https://raw.githubusercontent.com/danakt/russian-words/master/russian.txt');


constructWords($argv[1]);


function constructWords(string $word): string {
    $resultWordsArr = [];
    $resultWordList = '';

    $word = preg_replace("/[^\\p{L}]+/u", "", $word);
    if(strlen($word) == 0) {
        writeLog('Невалидные входные данные');
        return '';
    }

    $wordArr = preg_split('//u', $word, 0, PREG_SPLIT_NO_EMPTY);
    foreach($wordArr as &$char) {
        $char = mb_strtolower($char, 'UTF-8');
    }

    $wordBase = getWordBase();
    if(!$wordBase) {
        writeLog('Не удалось получить базу слов');
        return '';
    }

    $base = count($wordArr);
    $combinations = [];
    for ($i = 0; $i < pow($base, $base); $i++) {
        $temp = convertToNumberSystem($i, $base);
        if (count($temp) == 0) {
            continue;
        }
        $combinations[] = array_reverse($temp);
        if (!in_array(0, $temp)) {
            $temp[] = 0;
            $combinations[] = array_reverse($temp);
        }
    }
    $cnt = 0;
    foreach ($combinations as $combination) {
        $s = '';
        foreach ($combination as $v) {
            $s .= $wordArr[$v];
        }
        if ($s != ""){
            $cnt++;
    
            if(searchStrInBase($s, $wordBase)) {
                $resultWordsArr[] = $s;
            }
        }
    }
    
    $resultWordList = implode("\n", $resultWordsArr);

    writeLog($resultWordList);
    return $resultWordList;
}

function getWordBase(string $url = URL_WORD_BASE): string {
    $wordBase = file_get_contents($url);
    $wordBase = mb_strtolower(mb_convert_encoding($wordBase, 'UTF-8', 'Windows-1251'),"UTF-8");
    return $wordBase;
}

function searchStrInBase(string $str, string $wordBase): bool {
    if(preg_match("/\b" . preg_quote($str, "/") . "\b/u", $wordBase)) {
        return true;
    } else {
        return false;
    }
}

function convertToNumberSystem(int $value, int $base): array {
    $res = [];
    if ($value == 0) {     
        $res[] = 0;
        return $res;
    }
    while ($value > 0) {
        if (in_array($value % $base, $res)) {
            return [];
        }
        $res[] = $value % $base;
        $value = intdiv($value, $base);
    }
    return $res;
}

