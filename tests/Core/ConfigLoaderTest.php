<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand2\Core\ConfigLoader;

class ConfigLoaderTest extends TestCase
{
    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(
            ConfigLoader::class,
            new ConfigLoader('testBrain')
        );
    }
}
