<?php

namespace RPurinton\Discommand2\Core;

use RPurinton\Discommand2\Exceptions\ConfigurationException;
use RPurinton\Discommand2\Exceptions\LogException;

class ConfigLoader
{
    protected $config = [];
    protected $logger;
    protected $exceptionHandler;

    public function __construct(public $myName)
    {
        $this->logger = new Logger($myName);
        $this->exceptionHandler = new GlobalExceptionHandler($this->logger);
        foreach (glob(__DIR__ . "/../../conf.d/*.json") as $configFile) $this->config[basename($configFile, '.json')] = json_decode(file_get_contents($configFile), true);
        $this->log("ConfigLoader initialized");
        return $this;
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
            echo ("Debug: ConfigLoader::log() called before logger initialized: $message\n");
            return;
        }
        $this->logger->log($message, $level);
    }
}
