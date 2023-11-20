<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand2\Exceptions\FatalException;

class FatalExceptionTest extends TestCase
{
    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(
            FatalException::class,
            new FatalException('Test Fatal Exception')
        );
    }
}
