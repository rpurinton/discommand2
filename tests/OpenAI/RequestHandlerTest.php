<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand2\OpenAI\RequestHandler;

class RequestHandlerTest extends TestCase
{
    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(
            RequestHandler::class,
            new RequestHandler()
        );
    }
}
