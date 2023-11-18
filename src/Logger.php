<?php

namespace RPurinton\Discommand2;

class Logger
{
    private float $last_microttime = 0;

    public function __construct(private string $log_dir = __DIR__ . '/../logs.d')
    {
        $this->last_microttime = microtime(true);
        if (substr($this->log_dir, 0, 1) !== '/') $this->log_dir = __DIR__ . "/../" . $this->log_dir;
        if (!is_dir($this->log_dir)) mkdir($this->log_dir);
        $this->log("Logger initialized");
    }

    public function log($message)
    {
        $microtime = microtime(true);
        $diff = $microtime - $this->last_microttime;
        $this->last_microttime = $microtime;
        $diff = number_format($diff, 6, '.', '') . 's';
        $log_file = $this->log_dir . '/' . date('Y-m-d') . '.log';
        $log_message = "[" . date('Y-m-d H:i:s') . '.' . substr(number_format(microtime(true), 6, '.', ''), -6) . "] ($diff) $message\n";
        file_put_contents($log_file, $log_message, FILE_APPEND);
        echo "($diff) $message\n";
    }
}
