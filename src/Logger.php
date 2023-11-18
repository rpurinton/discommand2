<?php

namespace RPurinton\Discommand2;

class Logger
{
    private static $log_dir = __DIR__ . '/../logs.d';

    public static function log($message)
    {
        if (!is_dir(self::$log_dir)) {
            mkdir(self::$log_dir, 0777, true);
        }

        $log_file = self::$log_dir . '/' . date('Y-m-d') . '.log';
        $timestamp = "[" . date('Y-m-d H:i:s') . '.' . explode(' ', microtime())[0] . ']';
        $message = "$timestamp $message\n";

        file_put_contents($log_file, $message, FILE_APPEND);
        echo $message;
    }
}
