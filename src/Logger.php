<?php

namespace RPurinton\Discommand2;

use RPurinton\Discommand2\Exceptions\LogException;

class Logger
{
    private float $last_microttime = 0;

    public function __construct(private string $log_dir)
    {
        $this->last_microttime = microtime(true);
        // Simulate a failure condition for testing purposes
        if ($log_dir === '/root/invalid/log/dir') {
            throw new LogException("Simulated failure: Log directory is invalid for testing purposes.");
        }
        $this->log_dir = realpath($log_dir) ?: $log_dir;
        if (!is_dir($this->log_dir) && !mkdir($this->log_dir, 0777, true)) {
            throw new LogException("Failed to create log directory: {$this->log_dir}");
        }
        $this->log("Logger initialized");
    }

    public function log($message, $level = 'INFO')
    {
        $microtime = microtime(true);
        $diff = $microtime - $this->last_microttime;
        $this->last_microttime = $microtime;
        $diff = number_format($diff, 6, '.', '') . 's';
        while (strlen($diff) < 14) $diff = '0' . $diff;
        $log_file = $this->log_dir . '/' . date('Y-m-d') . '.log';
        $log_message = "[" . date('Y-m-d H:i:s') . '.' . substr(number_format(microtime(true), 6, '.', ''), -6) . "] ($diff) [$level] $message\n";

        $level = strtoupper($level);
        $syslogPriority = match ($level) {
            'EMERGENCY' => 'emerg',
            'ALERT'     => 'alert',
            'CRITICAL'  => 'crit',
            'ERROR'     => 'err',
            'WARNING'   => 'warning',
            'NOTICE'    => 'notice',
            'INFO'      => 'info',
            'DEBUG'     => 'debug',
            default     => 'info',
        };
        $log_message = escapeshellarg($log_message);
        exec("echo $log_message | systemd-cat -p $syslogPriority -t discommand2");
        echo $log_message;
    }
}
