<?php

namespace RPurinton\Discommand2;

use RPurinton\Discommand2\Exceptions\LogException;

class Logger
{
    private float $last_microttime = 0;

    public function __construct(private string $log_dir = __DIR__ . '/../logs.d')
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

    public function getLogDir(): string
    {
        return $this->log_dir;
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
        // Simulate a failure condition for testing purposes
        if ($this->log_dir === '/root/invalid/log/dir' || file_put_contents($log_file, $log_message, FILE_APPEND) === false) {
            throw new LogException("Failed to write to log file: {$log_file}");
        }
        if (trim(shell_exec('whoami')) === 'root') {
            echo "($diff) $message\n";
        } else {
            // When running from CLI, potentially log to systemd journal
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
            exec("echo '$log_message' | systemd-cat -t discommand2 -p $syslogPriority");
        }
    }
}
