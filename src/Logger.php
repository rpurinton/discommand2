<?php

namespace RPurinton\Discommand2;

class Logger
{
    public static function log($message)
    {
        error_log($message . "\n", 3, __DIR__ . '/../logs/app.log');
    }
}
