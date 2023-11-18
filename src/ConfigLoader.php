<?php

namespace RPurinton\Discommand2;

class ConfigLoader
{
    protected $config = [];

    public function __construct()
    {
        Logger::log("ConfigLoader Construct");
        $this->loadConfig();
    }

    public function __destruct()
    {
        Logger::log("ConfigLoader Destruct");
    }

    private function loadConfig()
    {
        Logger::log("ConfigLoader Load Config");
        $configFiles = glob(__DIR__ . "/../conf.d/*.json");
        foreach ($configFiles as $configFile) {
            $section = basename($configFile, '.json');
            $this->config[$section] = json_decode(file_get_contents($configFile), true);
        }
    }
}
