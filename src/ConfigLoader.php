<?php

namespace RPurinton\Discommand2;

use RPurinton\Discommand2\Exceptions\ConfigurationException;

class ConfigLoader
{
    protected $config = [];

    public function __construct()
    {
        try {
            foreach (glob(__DIR__ . "/../conf.d/*.json") as $configFile) {
                $configContent = file_get_contents($configFile);
                if ($configContent === false) {
                    throw new ConfigurationException("Failed to read configuration file: {$configFile}");
                }
                $configData = json_decode($configContent, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new ConfigurationException("Invalid JSON in configuration file: {$configFile}");
                }
                $this->config[basename($configFile, '.json')] = $configData;
            }
        } catch (ConfigurationException $e) {
            // Handle exception (log or rethrow)
            throw $e;
        }
    }
}
