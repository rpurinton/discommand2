<?php

namespace RPurinton\Discommand2;

class ConfigLoader
{
    protected $config = [];

    public function __construct()
    {
        foreach (glob(__DIR__ . "/../conf.d/*.json") as $configFile) $this->config[basename($configFile, '.json')] = json_decode(file_get_contents($configFile), true);
    }
}
