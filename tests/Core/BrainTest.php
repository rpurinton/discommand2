<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand2\Core\Brain;

class BrainTest extends TestCase
{
    public function testCanBeCreated(): void
    {
        $brain = new Brain('testBrain');
        $this->assertInstanceOf(
            Brain::class,
            $brain
        );
        $this->assertEquals('testBrain', $brain->myName);
        $brain->__destruct();
    }
}
