<?php

namespace RPurinton\Discommand2;

use RPurinton\Discommand2\Exceptions\ConfigurationException;

class ConfigLoader
{
    protected $config = [];

    public function __construct()
    {
        try {
            foreach (glob(__DIR__ . "/../conf.d/*.json") as $configFile) $this->config[basename($configFile, '.json')] = json_decode(file_get_contents($configFile), true);
        } catch (\Throwable $e) {
            throw new ConfigurationException("Failed to load configuration: {$e->getMessage()}");
        }
    }

    // Intentionally trigger a ConfigurationException
    public function loadInvalidConfig()
    {
        throw new ConfigurationException("Intentional exception for testing purposes.");
    }
}
