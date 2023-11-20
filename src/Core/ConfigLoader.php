<?php

namespace RPurinton\Discommand2\Core;

use RPurinton\Discommand2\Core\Logger;
use RPurinton\Discommand2\Exceptions\FatalException;

class ConfigLoader extends Logger
{
    protected $config = [];

    public function __construct(string $myName)
    {
        parent::__construct($myName);
        try {
            foreach (glob(__DIR__ . "/../../configs/*.json") as $configFile) $this->config[basename($configFile, '.json')] = json_decode(file_get_contents($configFile), true);
            if (count($this->config)) $this->log("ConfigLoader initialized");
            else throw new FatalException("No configuration files found in conf.d");
        } catch (\Throwable $e) {
            throw $e;
        } finally {
            return $this;
        }
    }

    public function getConfig(string $section, int|string $key = null): mixed
    {
        if ($key) return $this->config[$section][$key] ?? null;
        return $this->config[$section] ?? [];
    }
}
