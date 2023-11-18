<?php

namespace RPurinton\Discommand2;

class ConfigLoader
{
    protected $config = [];

    public function __construct()
    {
        echo ("ConfigLoader Construct\n");
        $this->loadConfig();
    }

    public function __destruct()
    {
        echo ("ConfigLoader Destruct\n");
    }

    private function loadConfig()
    {
        echo ("ConfigLoader Load Config\n");
        $configFiles = glob(__DIR__ . "/../conf.d/*.json");
        foreach ($configFiles as $configFile) {
            $section = basename($configFile, '.json');
            $this->config[$section] = json_decode(file_get_contents($configFile), true);
        }
    }
}
