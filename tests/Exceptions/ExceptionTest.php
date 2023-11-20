<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand2\Exceptions\Exception;

class ExceptionTest extends TestCase
{
    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(
            Exception::class,
            new Exception('Test Exception')
        );
    }
}
