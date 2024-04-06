<?php

$baseDirPath = __DIR__;
$logDirName = 'logs';

function setupLog() {
    global $baseDirPath;
    global $logDirName;

    if(!is_dir($baseDirPath . '/' . $logDirName)) {
        mkdir($logDirName);
    }

    error_reporting(E_ALL);
    ini_set('error_log', $logDirName . '/' . basename($baseDirPath) . '_error_' . date('Y-m-d') . '.log');
}

function writeLog($input) {
    global $baseDirPath;
    global $logDirName;

    $logFileName = basename($baseDirPath) . '_log_' . date('Y-m-d') . '.log';

    $type = gettype($input);

    switch($type) {
        case 'string':
            file_put_contents($logDirName . '/' . $logFileName, date('Y-m-d H:i:s') . ' ' . $input . PHP_EOL, FILE_APPEND);
            break;

        default:
            file_put_contents($logDirName . '/' . $logFileName, date('Y-m-d H:i:s') . ' ' . print_r($input, true) . PHP_EOL, FILE_APPEND);
            break;

    }
}

