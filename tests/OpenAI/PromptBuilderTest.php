<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand2\OpenAI\PromptBuilder;

class PromptBuilderTest extends TestCase
{
    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(
            PromptBuilder::class,
            new PromptBuilder()
        );
    }
}
