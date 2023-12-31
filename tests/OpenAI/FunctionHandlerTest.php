<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand\OpenAI\FunctionHandler;

class FunctionHandlerTest extends TestCase
{
    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(
            FunctionHandler::class,
            new FunctionHandler()
        );
    }
}
