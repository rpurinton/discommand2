<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand2\OpenAI\Client;
use RPurinton\Discommand2\Core\Brain;

class ClientTest extends TestCase
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
            Client::class,
            new Client($this->brain)
        );
    }
}
