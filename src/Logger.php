<?php

namespace RPurinton\Discommand2;

class Logger
{
    public static function log($message)
    {
        echo ($message . "\n");
        $log_dir = __DIR__ . '/../logs.d';
        if (!is_dir($log_dir)) mkdir($log_dir);
        $log_file = $log_dir . '/' . date('Y-m-d') . '.log';
        file_put_contents($log_file, date('Y-m-d H:i:s') . ' ' . $message . "\n", FILE_APPEND);
    }
}
