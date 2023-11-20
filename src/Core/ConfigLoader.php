<?php

namespace RPurinton\Discommand2\Core;

use finfo;
use RPurinton\Discommand2\Exceptions\ConfigurationException;
use RPurinton\Discommand2\Exceptions\LogException;

class ConfigLoader
{
    protected $config = [];
    protected ?Logger $logger = null;
    protected ?GlobalExceptionHandler $exceptionHandler = null;

    public function __construct(public $myName)
    {
        try {
            $this->exceptionHandler = new GlobalExceptionHandler($this->logger);
            $this->logger = new Logger($myName);
            foreach (glob(__DIR__ . "/../../conf.d/*.json") as $configFile) $this->config[basename($configFile, '.json')] = json_decode(file_get_contents($configFile), true);
            $this->log("ConfigLoader initialized");
        } catch (\Throwable $e) {
            $this->exceptionHandler->handleException($e);
            die();
        } finally {
            return $this;
        }
    }

    public function getConfig(string $section): array
    {
        return $this->config[$section] ?? [];
    }

    public function getLogger(): Logger
    {
        return $this->logger;
    }

    public function log(string $message, string $level = 'INFO'): void
    {
        if (!$this->logger) {
            echo "[ERROR][{$this->myName}] Logger not initialized yet: $message\n";
            return;
        }
        $this->logger->log($message, $level);
    }
}
