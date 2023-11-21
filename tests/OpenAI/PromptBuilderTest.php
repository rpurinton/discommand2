<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand\OpenAI\PromptBuilder;
use RPurinton\Discommand\Core\Brain;

class PromptBuilderTest extends TestCase
{
    private $brain;

    protected function setUp(): void
    {
        $this->brain = $this->createMock(Brain::class);
        $this->brain->myName = 'testBrain';
        $this->brain->method('log')->willReturn(true);
    }

    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(
            PromptBuilder::class,
            new PromptBuilder($this->brain)
        );
    }
}
