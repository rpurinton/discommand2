<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand\OpenAI\FunctionLoader;

class FunctionLoaderTest extends TestCase
{
    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(
            FunctionLoader::class,
            new FunctionLoader()
        );
    }
}
