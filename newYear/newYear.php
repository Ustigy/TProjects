<?php

declare(strict_types=1);
require_once('./helpers.php');
setupLog();

newYear($argv[1]);


function newYear(string $year, string $strStart = '01-01-2020 12:00:00', string $zone = 'Europe/Moscow') : string {
    $timeZoneMsk = new DateTimeZone($zone);
    $timeMsk = new DateTime($strStart, $timeZoneMsk);
    $countTimeZone = 3;

    if($year < $timeMsk->format('Y')) {
        writeLog('Входное значение меньше даты вылета');
        return '';
    }

    $timeZoneLocal = new DateTimeZone($zone);
    $timeLocal = new DateTime($strStart, $timeZoneLocal);
    $timeZoneLocalName = $zone;

    $isNewYear = false;

    while($timeLocal < new DateTime("31-12-$year 24:00:00", new DateTimeZone($timeZoneLocalName))) {
        if($isNewYear) {
            break;
        }

        $timeMsk->add(new DateInterval('PT2H'));
        $timeLocal->add(new DateInterval('PT2H'));

        $countTimeZone -= 3;
        if ($countTimeZone == -12) {
            $countTimeZone = 12;
        }
        $timeZoneLocalName = timezone_name_from_abbr("", $countTimeZone * 3600, 0);
        if ($countTimeZone == 6) { //Обрабатываем признанную php ошибку timezone_name_from_abbr
            $timeZoneLocalName="Indian/Chagos";
        }
        $timeLocal->setTimezone(new DateTimeZone($timeZoneLocalName));

        if($timeLocal > new DateTime("31-12-$year 24:00:00", new DateTimeZone($timeZoneLocalName))) {
            $isNewYear = true;
        } else {
            $timeMsk->add(new DateInterval('PT6H'));
            $timeLocal->add(new DateInterval('PT6H'));
        }
    }
    
    $timeZoneLocalName = timezone_name_from_abbr("", $countTimeZone * 3600, 0);
        if ($countTimeZone == 6) {
            $timeZoneLocalName = "Indian/Chagos";
        }

    $newYearLocal = new DateTime("31-12-$year 24:00:00", new DateTimeZone($timeZoneLocalName));
    
    if ($newYearLocal > $timeLocal) {
        $timeLocal->setTimezone(new DateTimeZone("Europe/Moscow"));

        writeLog($timeLocal->format('Y-m-d H:i:s'));
        return $timeLocal->format('H:i:s');
    } else {
        $newYearLocal->setTimezone(new DateTimeZone("Europe/Moscow"));

        writeLog($newYearLocal->format('Y-m-d H:i:s'));
        return $newYearLocal->format('H:i:s');
    }
    
}


