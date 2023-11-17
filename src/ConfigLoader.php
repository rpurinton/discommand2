<?php

namespace RPurinton\Discommand2;

class ConfigLoader
{
    protected $config = [];

    public function __construct()
    {
        $this->loadConfig();
    }

    private function loadConfig()
    {
        $configFiles = glob(__DIR__ . "/conf.d/*.json");
        foreach ($configFiles as $configFile) {
            $section = basename($configFile, '.json');
            $this->config[$section] = json_decode(file_get_contents($configFile), true);
        }
    }
}
