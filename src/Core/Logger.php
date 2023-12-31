<?php

namespace RPurinton\Discommand\Core;

use RPurinton\Discommand\Exceptions\FatalException;

class Logger
{
    public bool $alive = false;
    private float $boot_microtime = 0;
    private float $last_microttime = 0;
    public string $log_dir;

    public function __construct(public string $myName)
    {
        try {
            set_exception_handler([$this, 'handleException']);
            if (!is_dir("/home/$myName")) throw new FatalException("$myName has not been created. Please run 'newBrain.php $myName' first.");
            $this->boot_microtime = microtime(true);
            $this->last_microttime = microtime(true);
            $log_dir = "/home/$myName/logs.d";
            $this->log_dir = realpath($log_dir) ?: $log_dir;
            if (!is_dir($this->log_dir) && !mkdir($this->log_dir, 0777, true)) {
                throw new FatalException("Failed to create log directory: {$this->log_dir}");
            }
            $this->log("Booting $myName...");
        } catch (\Throwable $e) {
            echo $e->getMessage() . "\n";
            exit(1);
        }
    }

    public function log($message, $level = 'INFO'): bool
    {
        try {
            $microtime = microtime(true);
            $boot_diff = number_format($microtime - $this->boot_microtime, 6, '.', '');
            while (strlen($boot_diff) < 14) $boot_diff = '0' . $boot_diff;
            $diff = number_format($microtime - $this->last_microttime, 6, '.', '') . 's';
            while (strlen($diff) < 14) $diff = '0' . $diff;
            $this->last_microttime = $microtime;
            $log_file = $this->log_dir . '/' . date('Y-m-d') . '.log';
            $log_message = "[" . date('Y-m-d H:i:s') . '.' . substr(number_format(microtime(true), 6, '.', ''), -6) . "]($boot_diff:$diff)[$level][{$this->myName}] $message\n";
            if ($this->log_dir === '/invalid/log/dir' || !is_dir($this->log_dir)) throw new FatalException("Log directory is invalid");
            file_put_contents($log_file, $log_message, FILE_APPEND | LOCK_EX) or throw new FatalException("Failed to write to log file: {$log_file}");
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
            exec("echo $log_message | systemd-cat -p $syslogPriority -t discommand2", $output, $exitCode);
            if ($exitCode) throw new FatalException("Failed to write to syslog");
            return true;
        } catch (\Throwable $e) {
            echo $e->getMessage() . "\n";
            exit(1);
        }
    }

    public function handleException(\Throwable $exception): void
    {
        try {
            $this->log($exception->getMessage(), "ERROR") or throw new FatalException("Failed to log");
        } catch (FatalException $e) {
            echo ($e->getMessage() . "\n");
        } finally {
            if ($exception instanceof FatalException) {
                if ($this->alive) echo ("I'm sorry to inform you but {$this->myName} had a fatal error and has passed away. D:\n");
                exit(1);
            }
        }
    }
}
