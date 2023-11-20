<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand2\OpenAI\TokenCounter;

class TokenCounterTest extends TestCase
{
    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(
            TokenCounter::class,
            new TokenCounter()
        );
    }

    public function testCount(): void
    {
        $counter = new TokenCounter();
        $this->assertIsInt(
            $counter->count('Test string')
        );
    }
}
