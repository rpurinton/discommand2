<?php

namespace RPurinton\Discommand2;

use RPurinton\Discommand2\Exceptions\ConfigurationException;

class ConfigLoader
{
    protected $config = [];

    public function __construct()
    {
        // Existing constructor code...
    }

    // Intentionally trigger a ConfigurationException
    public function loadInvalidConfig()
    {
        throw new ConfigurationException("Intentional exception for testing purposes.");
    }
}
