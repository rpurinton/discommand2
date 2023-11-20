<?php

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand2\Core\ConfigLoader;
use RPurinton\Discommand2\Exceptions\ConfigurationException;

class ErrorHandlingTest extends TestCase
{
    public function testConfigurationException()
    {
        $this->expectException(ConfigurationException::class);
        $configLoader = new ConfigLoader("invalid-brain-name");
    }
}
