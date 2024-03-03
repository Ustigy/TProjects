<?php

class SomeClass {
    public function revertCharacters($str) {
        $strArr = preg_split('//u', $str, null, PREG_SPLIT_NO_EMPTY);
    
        $revertArr = [];
        $tempArr = [];
        $idxUpperArr = [];
    
        foreach($strArr as $i => $char) {
            if(preg_match('/[a-zA-Z0-9а-яА-ЯёЁ]/', $char)) {
                $tempArr[] = $char;
    
                if($char !== mb_strtolower($char, 'UTF-8')) {
                    $idxUpperArr[] = $i;
                }
            } else {
                if($tempArr) {
                    $tempArr = array_reverse($tempArr);
                    $revertArr = array_merge($revertArr, $tempArr);
                }
                
                $revertArr[] = $char;
                $tempArr = [];
            }
        }
    
        if(count($tempArr) !== 0) {
            $tempArr = array_reverse($tempArr);
            $revertArr = array_merge($revertArr, $tempArr);
        }
    
        foreach($revertArr as $key => &$val) {
            if(in_array($key, $idxUpperArr)) {
                $val = mb_strtoupper($val, 'UTF-8');
            } else {
                $val = mb_strtolower($val, 'UTF-8');
            }
        }
        return implode('', $revertArr);
    
    }
    
}

