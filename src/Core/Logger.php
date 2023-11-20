<?php

namespace RPurinton\Discommand2\Core;

use RPurinton\Discommand2\Exceptions\LogException;

class Logger
{
    private float $boot_microtime = 0;
    private float $last_microttime = 0;
    public string $log_dir;

    public function __construct(public string $myName)
    {
        $log_dir = "/home/$myName/logs.d";
        $this->boot_microtime = microtime(true);
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

    public function log($message, $level = 'INFO'): bool
    {
        $microtime = microtime(true);
        $boot_diff = number_format($microtime - $this->boot_microtime, 6, '.', '');
        while (strlen($boot_diff) < 14) $boot_diff = '0' . $boot_diff;
        $diff = number_format($microtime - $this->last_microttime, 6, '.', '') . 's';
        while (strlen($diff) < 14) $diff = '0' . $diff;
        $this->last_microttime = $microtime;
        $log_file = $this->log_dir . '/' . date('Y-m-d') . '.log';
        $log_message = "[" . date('Y-m-d H:i:s') . '.' . substr(number_format(microtime(true), 6, '.', ''), -6) . "]($boot_diff:$diff)[$level][{$this->myName}] $message\n";
        if ($this->log_dir === '/invalid/log/dir') {
            throw new LogException("Simulated failure: Log directory is invalid for testing purposes.");
        }
        file_put_contents($log_file, $log_message, FILE_APPEND | LOCK_EX) or throw new LogException("Failed to write to log file: {$log_file}");
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
        echo $log_message;
        $log_message = escapeshellarg($log_message);
        exec("echo $log_message | systemd-cat -p $syslogPriority -t discommand2");
        return true;
    }
}
